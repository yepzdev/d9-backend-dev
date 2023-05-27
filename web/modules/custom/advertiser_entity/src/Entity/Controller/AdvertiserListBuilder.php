<?php

namespace Drupal\advertiser_entity\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AdvertiserListBuilder extends EntityListBuilder
{
    protected $urlGenerator;

    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
    {
        return new static(
            $entity_type,
            $container->get('entity_type.manager')->getStorage($entity_type->id()),
            $container->get('url_generator')
        );
    }

    /**
     * {@inheritdoc}
     *
     * We override ::render() so that we can add our own content above the table.
     * parent::render() is where EntityListBuilder creates the table using our
     * buildHeader() and buildRow() implementations.
     */
    public function render()
    {
        $build['description'] = [
            '#markup' => $this->t('Content Entity Example implements a Advertiser model. These advertisers are fieldable entities. You can manage the fields on the <a href="@adminlink">Advertiser admin page</a>.', [
                '@adminlink' => $this->urlGenerator->generateFromRoute('advertiser_entity.advertiser_settings'),
            ]),
        ];
        $build['table'] = parent::render();
        return $build;
    }

    /**
     * Constructs a new AdvertiserListBuilder object.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type definition.
     * @param \Drupal\Core\Entity\EntityStorageInterface $storage
     *   The entity storage class.
     * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
     *   The url generator.
     */

    public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator)
    {
        parent::__construct($entity_type, $storage);
        $this->urlGenerator = $url_generator;
    }

    /**
     * {@inheritdoc}
     *
     * Building the header and content lines for the advertiser list.
     *
     * Calling the parent::buildHeader() adds a column for the possible actions
     * and inserts the 'edit' and 'delete' links as defined for the entity type.
     */

    public function buildHeader()
    {
        $header['id'] = $this->t('AdvertiserID');
        $header['name'] = $this->t('Name');
        $header['first_name'] = $this->t('First Name');
        $header['role'] = $this->t('Role');

        return $header + parent::buildHeader();
    }

    /**
     * {@inheritDoc}
     */

    public function buildRow(EntityInterface $entity)
    {
        /* @var $entity \Drupal\advertiser_entity\Entity\Advertiser */

        # WHERE IS USER_ID ?
        $row['id'] = $entity->id();
        $row['name'] = $entity->toLink()->toString();
        $row['first_name'] = $entity->first_name->value;
        $row['role'] = $entity->role->value;

        return $row + parent::buildRow($entity);
    }
}
