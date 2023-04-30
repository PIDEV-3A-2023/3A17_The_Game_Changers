<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employee')]
class EmployeeController extends AbstractController
{

    #[Route('/', name: 'app_employee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmployeeRepository $employeeRepository): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeRepository->save($employee, true);

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EmployeeRepository $employeeRepository): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeRepository->save($employee, true);

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EmployeeRepository $employeeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->request->get('_token'))) {
            $employeeRepository->remove($employee, true);
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/calendar', name: 'app_employee_calendar', methods: ['GET'])]
    public function calendar($id)
    {
        // Retrieve the employee entity from the database using the provided ID
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);

        // Get the start and end dates for the events to display on the calendar
        $startDate = new \DateTime('now');
        $endDate = new \DateTime('now + 1 month');

        // Retrieve the events for the employee within the specified date range
        $events = $employee->getEventsInRange($startDate, $endDate);

        // Format the events data as an array of arrays
        $calendarData = array();
        foreach ($events as $event) {
            $calendarData[] = array(
                'title' => $event->getTitle(),
                'start' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d H:i:s'),
                // Add any additional event data here...
            );
        }

        // Return the calendar data as a JSON response
        return $this->json($calendarData);
    }
    /**
     * @Route("/employee/{id}/rate", name="app_employee_rate", methods={"POST"})
     */
    public function rate(Request $request, EntityManagerInterface $entityManager,int $id): Response
    {
        // get the employee with the specified id
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        // check if the employee exists
        if (!$employee) {
            throw $this->createNotFoundException('The employee does not exist');
        }

        // get the rating from the request data
        $rating = $request->request->get('rating');

        // TODO: save the rating for the employee in the database
        $employee->setRating($rating);
        $entityManager->flush();
        // return a success response
        return $this->redirectToRoute('app_employee_show', ['id' => $id]);
    }

}
