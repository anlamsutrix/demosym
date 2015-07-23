<?php

namespace StudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * School
 *
 * @ORM\Table(name="school")
 * @ORM\Entity(repositoryClass="StudentBundle\Entity\SchoolRepository")
 *
 */
class School
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
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * 
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

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
     * @return School
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
     * Set address
     *
     * @param string $address
     * @return School
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return School
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return School
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="school")
     */
    protected $student;

    public function __construct()
    {
        $this->student = new ArrayCollection();
    }

    /**
     * Add student
     *
     * @param \StudentBundle\Entity\student $student
     * @return School
     */
    public function addStudent(\StudentBundle\Entity\student $student)
    {
        $this->student[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \StudentBundle\Entity\student $student
     */
    public function removeStudent(\StudentBundle\Entity\student $student)
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
