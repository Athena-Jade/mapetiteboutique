<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

 
    


####### CREATION findWithSearch  #############

    /**
     * Requête permettant de récupérer les produits en fonction de la recherche de l'user            
     *@return Product[] 
     */
    public function findWithSearch(Search $search)  
    {
        $query = $this                            // création de la requête
            ->createQueryBuilder('p')            //  p signifie produit  (table produit)
            ->select('c', 'p')                  // c signifie categorie, p signifie produit
            ->join('p.category', 'c');         // jointure entre les catégories du produit et la table category

        if(!empty($search->categories)){        // lorsque et uniquement les catégories ont été cochées, rajoute dans cette requête Andwhere qui permet de filtrer les catégories. 
            $query = $query
                ->andWhere('c.id IN (:categories)')   // id des categories soient dans la liste des categories
                ->setParameter('categories', $search->categories); 
                   
                
        }



        if(!empty($search->string)){  // es ce qu'une recherche textuelle a été cochée ?
            $query = $query
                ->andWhere('p.name LIKE :string')     // es ce que le nom du produit ressemble à string
                ->setParameter('string', "%{$search->string}%");   // recherche par texte

        }
            
            return $query->getQuery()->getResult();
    }
    
    
    













    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
