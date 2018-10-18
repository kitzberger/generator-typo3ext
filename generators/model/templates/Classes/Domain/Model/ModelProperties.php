<?php
namespace <%- VendorName %>\<%- ExtKey %>\Domain\Model;

class <%- ModelName %> extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
// BEGIN_PROPERTY_DEF
    /**
     * @var <%- propertyType %>
     */
    protected $<%- propertyName %> = <%- propertyDefault %>;
// END_PROPERTY_DEF

// BEGIN_OBJECT_STORAGE_DEF
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<<%- propertyType %>>
     */
    protected $<%- propertyName %> = <%- propertyDefault %>;
// END_OBJECT_STORAGE_DEF

    /**
     * __construct
     */
    public function __construct()
    {
// BEGIN_CONSTRUCT_OBJECT_STORAGE
        $this-><%- propertyName %> = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
// END_CONSTRUCT_OBJECT_STORAGE
    }

// BEGIN_PROPERTY_XETTERS
    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
// END_PROPERTY_XETTERS

// BEGIN_OBJECT_STORAGE_XETTERS
    /**
     * Adds a <%- propertyName %>
     *
     * @param <%- propertyType %> $<%- propertyName %>
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<<%- propertyType %>> <%- propertyName %>
     */
    public function add<%- PropertyName %>(<%- propertyType %> $<%- propertyName %>)
    {
        $this->characteristic->attach($<%- propertyName %>);
    }

    /**
     * Removes a <%- propertyName %>
     *
     * @param <%- propertyType %> $<%- propertyName %>ToRemove
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<<%- propertyType %>> characteristics
     */
    public function remove<%- PropertyName %>(<%- propertyType %> $<%- propertyName %>ToRemove)
    {
        $this->characteristics->detach($<%- propertyName %>ToRemove);
    }

    /**
     * Returns the <%- propertyName %>s
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<<%- propertyType %>> <%- propertyName %>
     */
    public function get<%- PropertyName %>s()
    {
        return $this-><%- propertyName %>s;
    }

    /**
     * Sets the <%- propertyName %>s
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<<%- propertyType %>> $<%- propertyName %>s
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<<%- propertyType %>> <%- propertyName %>s
     */
    public function setCharacteristics(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $<%- propertyName %>s)
    {
        $this-><%- propertyName %>s = $<%- propertyName %>s;
    }
// END_OBJECT_STORAGE_XETTERS
}
