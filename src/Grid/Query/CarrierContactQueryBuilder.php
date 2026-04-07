<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

namespace Novanta\CarrierContact\Grid\Query;

use Doctrine\DBAL\Connection;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicator;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class CarrierContactQueryBuilder extends AbstractDoctrineQueryBuilder
{
    private $searchCriteriaApplicator;

    public function __construct(
        Connection $connection,
        $dbPrefix,
        DoctrineSearchCriteriaApplicator $criteriaApplicator,
    ) {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $criteriaApplicator;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $queryBuilder = $this->getQueryBuilder($searchCriteria->getFilters());
        $queryBuilder->select('
            cc.id_carrier_contact,
            IFNULL(c.name, \'---\') as carrier_name,
            cc.name as name,
            cc.phone,
            cc.email1,
            cc.email2
        ');

        $this->searchCriteriaApplicator->applyPagination($searchCriteria, $queryBuilder);
        $this->searchCriteriaApplicator->applySorting($searchCriteria, $queryBuilder);

        return $queryBuilder;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $queryBuilder = $this->getQueryBuilder($searchCriteria->getFilters());
        $queryBuilder->select('COUNT(cc.id_carrier_contact)');

        return $queryBuilder;
    }

    protected function getQueryBuilder(array $filters)
    {
        $queryBuilder = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'carrier_contact', 'cc')
            ->leftJoin('cc', $this->dbPrefix . 'carrier', 'c',
                'cc.id_carrier = c.id_reference')
            ->add('where', 'c.id_carrier = (SELECT MAX(id_carrier) FROM ps_carrier where id_reference = cc.id_carrier)');

        foreach ($filters as $filterName => $filterValue) {
            if ('carrier_name' === $filterName) {
                $queryBuilder->andWhere("c.name LIKE :$filterName");
                $queryBuilder->setParameter($filterName, '%' . $filterValue . '%');

                continue;
            }

            if ('name' === $filterName) {
                $queryBuilder->andWhere("cc.name LIKE :$filterName");
                $queryBuilder->setParameter($filterName, '%' . $filterValue . '%');

                continue;
            }

            if ('phone' === $filterName) {
                $queryBuilder->andWhere("cc.phone LIKE :$filterName");
                $queryBuilder->setParameter($filterName, '%' . $filterValue . '%');

                continue;
            }

            $queryBuilder->andWhere("$filterName LIKE :$filterName");
            $queryBuilder->setParameter($filterName, '%' . $filterValue . '%');
        }

        return $queryBuilder;
    }
}
