<?php

namespace StudentBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="StudentBundle\Entity\SubjectRepository")
 */
class Subject
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="time", type="string", length=20)
     */
    private $time;

    /**
     * @ORM\ManyToMany(targetEntity="Student", mappedBy="subject")
     * @ORM\JoinTable(name="user_roles")
     */
    private $student;

    public function __construct()
    {
        $this->student = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Subject
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set time
     *
     * @param string $time
     * @return Subject
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Add student
     *
     * @param \StudentBundle\Entity\Student $student
     * @return Subject
     */
    public function addStudent(\StudentBundle\Entity\Student $student)
    {
        $this->student[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \StudentBundle\Entity\Student $student
     */
    public function removeStudent(\StudentBundle\Entity\Student $student)
    {
        $this->student->removeElement($student);
    }

    /**
     * Get student
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudent()
    {
        return $this->student;
    }
}
