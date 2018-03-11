<?php

namespace Xml\Data;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    /**
     * @var Subject|MockObject
     */
    private $subject;

    /**
     * @var CallbackStorage
     */
    private $storage;

    protected function setUp()
    {
        parent::setUp();
        $this->storage = new CallbackStorage();
        $this->subject = $this->getMockForAbstractClass(
            Subject::class,
            [$this->storage],
            '',
            true,
            true,
            true,
            $mockedMethods = ['getCallback']
        );


    }

    public function testSetData()
    {
        $data = [
            'input_attribute' => 'alaa'
        ];

        $this->subject->expects($this->any())
            ->method('getMappedAttributes')
            ->will($this->returnValue(['output_attribute' => 'input_attribute']));

        $this->subject->setData($data);

        $expected = ['output_attribute' => 'alaa'];
        $this->assertEquals($expected, $this->subject->getData());
    }

    public function testAddSubject()
    {
        $personData = [
            'name' => 'Alaa Al-Maliki',
            'profession' => 'Software Engineer',
            'years' => 4
        ];

        $person = new PersonSubjectMock(new CallbackStorage());
        $person->setData($personData);
        $peopleSubject = new PeopleSubjectMock(new CallbackStorage());
        $peopleSubject->setData([
            'company' => 'ABC network communication'
        ]);
        $peopleSubject->addSubject($person);

        $expected = [
            'company' => 'ABC network communication',
            'person' => [
                'name' => 'Alaa Al-Maliki',
                'job' => 'Software Engineer',
                'experience' => 4
            ]
        ];

        $this->assertEquals($expected, $peopleSubject->getData());
        $this->assertEquals($expected, $peopleSubject->toArray());
    }

    public function testAddSubjectWithNodeName()
    {
        $personData = [
            'name' => 'Alaa Al-Maliki',
            'profession' => 'Software Engineer',
            'years' => 4
        ];

        $person = new PersonSubjectMock(new CallbackStorage());
        $person->setData($personData);
        $peopleSubject = new PeopleSubjectMock(new CallbackStorage());
        $peopleSubject->setData([
            'company' => 'ABC network communication'
        ]);
        $peopleSubject->addSubject($person, 'person');

        $expected = [
            'company' => 'ABC network communication',
            'person' => [
                'name' => 'Alaa Al-Maliki',
                'job' => 'Software Engineer',
                'experience' => 4
            ]
        ];

        $this->assertEquals($expected, $peopleSubject->getData());
        $this->assertEquals($expected, $peopleSubject->toArray());
    }

    public function testAddSubjects()
    {
        $personData = [
            [
                'name' => 'Alaa Al-Maliki',
                'profession' => 'Software Engineer',
                'years' => 4
            ],
            [
                'name' => 'John Doe',
                'profession' => 'Marketing Manager',
                'years' => 7
            ]
        ];

        $personsData = [];
        foreach ($personData as $data) {
            $person = new PersonSubjectMock(new CallbackStorage());
            $person->setData($data);
            $personsData[] = $person;
        }

        $peopleSubject = new PeopleSubjectMock(new CallbackStorage());
        $peopleSubject->setData(['company' => 'ABC network communication']);
        $peopleSubject->addSubjects($personsData, 'persons');

        $expected = [
            'company' => 'ABC network communication',
            'persons' => [
                [
                    'person' => [
                        'name' => 'Alaa Al-Maliki',
                        'job' => 'Software Engineer',
                        'experience' => 4,
                    ]
                ],
                [
                    'person' => [
                        'name' => 'John Doe',
                        'job' => 'Marketing Manager',
                        'experience' => 7,
                    ]
                ]
            ]
        ];

        $this->assertTrue($peopleSubject->hasSubjects());
        $this->assertNotEmpty($peopleSubject->getSubjects());
        $this->assertEquals($expected, $peopleSubject->getData());
    }
}

class PeopleSubjectMock extends Subject
{
    protected $nodeName = 'people';

    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return ['company' => 'company'];
    }
}

class PersonSubjectMock extends Subject
{
    protected $nodeName = 'person';
    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [
            'name' => 'name',
            'job' => 'profession',
            'experience' => 'years'
        ];
    }
}
