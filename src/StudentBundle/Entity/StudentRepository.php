<?php

namespace StudentBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
/**
 * StudentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class StudentRepository extends EntityRepository
{
    /*
     * @param $order
     * Get student by school
     */

    function getAll (){
        $select = $this->createQueryBuilder('u') ;
        $select
                ->orderBy('u.id', 'ASC');
        return $select->getQuery()->getResult();
    }
    /*
     * @param $order
     * Get student by school
     */

    function getOrderBy ($order){
        $select = $this->createQueryBuilder('u') ;
        $select
                ->where('u.id !=1')
                ->orderBy('u.age', $order);
        return $select->getQuery()->getResult();
    }
    /*
     * @param $id, $age_start, $age_end
     * Get student by school where age $age_start -> $age_end
     */
    function getStudentBySchool_between ($id, $age_start, $age_end){
        $select = $this->createQueryBuilder('u');
        $select
                ->where('u.school ='.$id)
                ->andWhere($select->expr()->between('u.age', $age_start, $age_end))
                ->orderBy('u.age', 'ASC');
        return $select->getQuery()->getResult();
    }

    /*
     * @param $id, $age
     * Get student by school where age <= $age
     */
    function getStudentBySchool_lte ($id, $age = 12){
        $select = $this->createQueryBuilder('u');
        $select
                ->where('u.school ='.$id)
                ->andWhere($select->expr()->orX($select->expr()->lte('u.age', $age)))
                ->orderBy('u.age', 'ASC');
        return $select->getQuery()->getResult();
    }

    /*
     * @param $id, $age
     * Get student by school andwhere age = $age
     */
    function getStudentBySchool_eq ($id, $age = 12){
        $select = $this->createQueryBuilder('u');
        $select
                ->where('u.school ='.$id)
                ->andWhere($select->expr()->orX($select->expr()->eq('u.age', $age)))
                ->orderBy('u.age', 'ASC');
        return $select->getQuery()->getResult();
    }
    /*
     * @param $schoolId
     * Get student leftJoin school where by school_id
     */
    function getStudentLeftJoin($schoolId){

        $select = $this->createQueryBuilder('t');
        $select
                ->leftJoin('t.school', 's')
                ->where($select->expr()->andX(
                        $select->expr()->eq('s.id', $schoolId),
                        $select->expr()->like('s.name',$select->expr()->literal('%FPT%'))))
                ->orderBy('t.age', 'ASC');
        return $select->getQuery()->getResult();
    }
    /*
     * @param $name
     * Get student by name
     */
    function getStudentByName($name){

        $select = $this->createQueryBuilder('t');
         $select
                ->where($select->expr()->andX($select->expr()->like('t.name', $select->expr()->literal('%'.$name.'%'))))
                ->orderBy('t.age', 'ASC');
        return $select->getQuery()->getResult();
    }
    /*
     * @param $name
     * Get student by name
     */
    function searchStudentBySchool($name, $id){

        $select = $this->createQueryBuilder('t');
         $select
                ->where($select->expr()->eq('t.school', $id))
                ->andWhere($select->expr()->andX($select->expr()->like('t.name', $select->expr()->literal('%'.$name.'%'))))
                ->orderBy('t.age', 'ASC');
        return $select->getQuery()->getResult();
    }
    /*
     * get max position
     */
    function getPositionMax($id){
        $select = $this->createQueryBuilder('t');
        $select
                ->where($select->expr()->andX($select->expr()->eq('t.school', $id)))
                ->select('MAX(t.position)');
        return $select->getQuery()->getSingleScalarResult();
    }
    /*
     * get min position
     */
    function getPositionMin($id){
        $select = $this->createQueryBuilder('t');
        $select
                ->where($select->expr()->andX($select->expr()->eq('t.school', $id)))
                ->select('MIN(t.position)');
        return $select->getQuery()->getSingleScalarResult();
    }
}