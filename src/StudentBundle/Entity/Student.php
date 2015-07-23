<?php

namespace StudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use StudentBundle\Entity\School;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="StudentBundle\Entity\StudentRepository")
 */
class Student
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
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\File(maxSize="6000000")
     */
    private $image;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="render", type="string", length=10)
     */
    private $render;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="school_id", type="integer")
     */
    private $schoolId;

    /**
     * @ORM\ManyToOne(targetEntity="School", inversedBy="student")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    protected $school;
    /**
     * @ORM\ManyToMany(targetEntity="Subject", inversedBy="student")
     *
     */
    private $subject;


    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a doc file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $brochure;


    public function __construct()
    {
        $this->subject = new ArrayCollection();
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
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
     * @return Student
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
     * Set image
     *
     * @param string $image
     * @return Student
     * @param UploadedFile $file
     */
    public function setImage($image)
    {
            $this->image = $image;

    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Student
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set render
     *
     * @param string $render
     * @return Student
     */
    public function setRender($render)
    {
        $this->render = $render;

        return $this;
    }

    /**
     * Get render
     *
     * @return string
     */
    public function getRender()
    {
        return $this->render;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Student
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set schoolId
     *
     * @param integer $schoolId
     * @return Student
     */
    public function setSchoolId($schoolId)
    {

        $this->schoolId = $schoolId;

        return $this;
    }

    /**
     * Get schoolId
     *
     * @return integer
     */
    public function getSchoolId()
    {
        return $this->schoolId;
    }
    /**
     * Set school
     *
     * @param \StudentBundle\Entity\School $school
     * @return Student
     */
    public function setSchool(\StudentBundle\Entity\School $school = null)
    {
        $this->school = $school;
        return $this;
    }

    /**
     * Get school
     *
     * @return \StudentBundle\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }



    public function getFullImagePath() {
        return null === $this->image ? null : $this->getUploadRootDir(). $this->image;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir()."/";
    }

    protected function getTmpUploadRootDir() {
        return $_SERVER['DOCUMENT_ROOT']. 'uploads/images';
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadImage() {
        // the file property can be empty if the field is not required
        if (null === $this->image) {
            return;
        }

        $this->image->move($this->getUploadRootDir(), $this->id.'.'.$this->image->getClientOriginalName());
//        if(!$this->id){
//            $this->image->move($this->getTmpUploadRootDir(), $this->image->getClientOriginalName());
//        }else{
//            $this->image->move($this->getUploadRootDir(), $this->id.'.'.$this->image->getClientOriginalName());
//        }
        $this->setImage($this->id.'.'.$this->image->getClientOriginalName());
    }

    /**
     * @ORM\PostPersist()
     */
    public function moveImage()
    {
        if (null === $this->image) {
            return;
        }
        if(!is_dir($this->getUploadRootDir())){
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->image, $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir().$this->id.'.'.$this->image->getClientOriginalName());
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeImage()
    {
        unlink($this->getFullImagePath());
        rmdir($this->getUploadRootDir());
    }

    /**
     * Add subject
     *
     * @param \StudentBundle\Entity\Subject $subject
     * @return Student
     */
    public function addSubject(\StudentBundle\Entity\Subject $subject)
    {
        $this->subject[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param \StudentBundle\Entity\Subject $subject
     */
    public function removeSubject(\StudentBundle\Entity\Subject $subject)
    {
        $this->subject->removeElement($subject);
    }

    /**
     * Get subject
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /*
     *
     */
    public function getBrochure()
    {
        return $this->brochure;
    }

    public function setBrochure($brochure)
    {
        $this->brochure = $brochure;

        return $this;
    }
}
