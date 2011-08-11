<?php

namespace Train\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Train\MainBundle\Entity\Category;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Train\MainBundle\Entity\ProductRepository")
 * @Assert\Callback(methods={"isNameValid"})
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Sluggable(slugField="slug")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Gedmo\Slug
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     * @Assert\Min(0)
     * @Assert\Max(5000)
     *
     */
    protected $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * 
     */
    protected $category;

    /**
     * When the Product was created
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }   

    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @Assert\False(message="Name cannot be same as description")
     */
    public function isNameEqualDescription()
    {
        return ($this->name == $this->description);
    }

    public function __toString()
    {
        return $this->name;
    }

    public function isNameValid(ExecutionContext $context)
    {
        if ($this->name == 'Test')
        {
            $property_path = $context->getPropertyPath() . '.name';
            $context->setPropertyPath($property_path);
            $context->addViolation('This name sounds totally fake', array(), null);

        }
    }
}
