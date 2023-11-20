<?php

namespace Test\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AuthorRepository $repository;
    private string $path = '/author/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client     = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Author::class);
        $this->manager    = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function test_index(): void
    {
        $crawler = $this->client->request('GET', '/authors');

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Author index');
    }

    public function test_new(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'author[firstName]' => 'Testing',
            'author[lastName]'  => 'Testing',
        ]);

        self::assertResponseRedirects('/authors');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function test_show(): void
    {
        $fixture = new Author();
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Author');
    }

    public function test_edit(): void
    {
        $fixture = new Author();
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'author[firstName]' => 'Something New',
            'author[lastName]'  => 'Something New',
        ]);

        self::assertResponseRedirects('/authors');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFirstName());
        self::assertSame('Something New', $fixture[0]->getLastName());
    }

    public function test_remove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Author();
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/authors');
    }
}
