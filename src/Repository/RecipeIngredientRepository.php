<?php

namespace App\Repository;

use App\Entity\RecipeIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RecipeIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeIngredient[]    findAll()
 * @method RecipeIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeIngredientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecipeIngredient::class);
    }
    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('ri.amount', 'DESC');
    }
    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('ri');
    }
    /**
     * Save record.
     *
     * @param \App\Entity\RecipeIngredient $recipe_ingredient RecipeIngredient entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(RecipeIngredient $recipe_ingredient): void
    {
        $this->_em->persist($recipe_ingredient);
        $this->_em->flush($recipe_ingredient);
    }
    /**
     * Delete record.
     *
     * @param \App\Entity\RecipeIngredient $recipe_ingredient RecipeIngredient entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(RecipeIngredient $recipe_ingredient): void
    {
        $this->_em->remove($recipe_ingredient);
        $this->_em->flush($recipe_ingredient);
    }
    // /**
    //  * @return RecipeIngredient[] Returns an array of RecipeIngredient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecipeIngredient
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
