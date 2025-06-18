<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Form\ProviderType;
use App\Repository\ProviderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProviderController extends AbstractController
{
    /**
     * @Route("/provider", name="provider_root")
     */
    public function providerRedirect()
    {
        return $this->redirectToRoute('app_provider', ['_locale' => 'es']);
    }

    /**
     * @Route("/provider", name="app_provider")
     */
    public function index(ProviderRepository $providerRepository): Response
    {
        // El repositorio busca todos los registros
        $providers = $providerRepository->findAll();

        return $this->render('provider/index.html.twig', [
            'providers' => $providers,
        ]);
    }

    /**
     * @Route("/provider/{id}", name="provider_show", requirements={"id"="\d+"}) // Pide que el id sea un dígito
     */
    public function show(int $id, ProviderRepository $providerRepository): Response
    {
        // Busca al proveedor por ID
        $provider = $providerRepository->find($id);

        // Si no lo encuentra, lanza un error 404
        if (!$provider) {
            throw $this->createNotFoundException('Proveedor no encontrado');
        }

        // Renderiza la vista show.html.twig con el proveedor
        return $this->render('provider/show.html.twig', [
            'provider' => $provider,
        ]);
    }


    /**
     * @Route("/provider/new", name="provider_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $provider = new Provider();

        // Crea el formulario con la clase que hay en la carpeta Form
        $form = $this->createForm(ProviderType::class, $provider);

        // Revisa que el formulario se haya recibido correctamente
        $form->handleRequest($request);

        // Si el formulario es válido
        if ($form->isSubmitted() && $form->isValid()) {
            // Crea automáticamente las fechas
            $now = new \DateTimeImmutable();
            $provider->setCreatedAt($now);
            $provider->setUpdatedAt($now);

            // Guarda al proveedor en la BD
            $em->persist($provider);
            $em->flush();

            // Redirige al índice de proveedores
            return $this->redirectToRoute('app_provider');
        }

        // Renderiza la vista del formulario (la variable es para alternar entre crear o editar)
        return $this->render('provider/form.html.twig', [
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    /**
     * @Route("/provider/{id}/edit", name="provider_edit", requirements={"id"="\d+"})
     */
    public function edit(int $id, Request $request, ProviderRepository $providerRepository, EntityManagerInterface $em): Response
    {
        // Busca al proveedor por ID
        $provider = $providerRepository->find($id);

        // Si no lo encuentra, devuelve el error
        if (!$provider) {
            throw $this->createNotFoundException('Proveedor no encontrado');
        }

        // Crea el formulario con los datos ya existentes
        $form = $this->createForm(ProviderType::class, $provider);

        // Revisa que el formulario se haya recibido correctamente
        $form->handleRequest($request);

        // Si el formulario es correcto
        if ($form->isSubmitted() && $form->isValid()) {
            // Cambia la fecha del updatedAt
            $provider->setUpdatedAt(new \DateTimeImmutable());

            // Guarda los cambios en la BD
            $em->flush();

            // Redirige al índice
            return $this->redirectToRoute('app_provider');
        }

        // Muestra el formulario para editar (es el mismo que el de crear, solo que cambia el texto)
        return $this->render('provider/form.html.twig', [
            'form' => $form->createView(),
            'provider' => $provider,
            'edit' => true,
        ]);
    }

    /**
     * @Route("/provider/{id}/delete", name="provider_delete", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function delete(Request $request, Provider $provider, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $provider->getId(), $submittedToken)) {
            $em->remove($provider);
            $em->flush();

            $this->addFlash('success', 'Proveedor eliminado correctamente.');
        } else {
            $this->addFlash('error', 'Token CSRF inválido.');
        }

        return $this->redirectToRoute('app_provider');
    }
}
