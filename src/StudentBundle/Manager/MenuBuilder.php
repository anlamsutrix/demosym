<?php
namespace StudentBundle\Manager;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use StudentBundle\Entity\Student;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuBuilder
{
    private $factory;
    private $container;
    /**
     * @param FactoryInterface $factory
     */
    public function __construct($factory ,ContainerInterface $container)
    {
        $this->container = $container;
        $this->factory = $factory;
    }
    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', array('route' => 'admin'));
        $menu->addChild('List User', array('route' => 'admin_user'));
        /*
         * add sub menu:
         */
        $menu['List User']->addChild(' -->Add User', array('route' => 'admin_user_new'));

        $menu->addChild('List Schools', array('route' => 'school'));
        $menu->addChild('List Students', array('route' => 'student'));

        return $menu;
    }
    public function createSideMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('side');
        $em = $this->container->get('doctrine')->getManager();
        $school = $em->getRepository('StudentBundle:School')->findBy(array(), array('position'=>'DESC'));
        foreach ($school as $item){
            $menu->addChild($item->getName(), array('route' => 'student_by_school',
                'routeParameters' => array('id' => $item->getId())));
            $student = $em->getRepository('StudentBundle:Student')->findBy(array('school'=>$item->getId()),array('position' => 'DESC'));
            foreach ($student as $st)
            $menu[$item->getName()]->addChild('-----'.$st->getName(), array('route' => 'student_show',
                'routeParameters' => array('id' => $st->getId())
                ));

        }
        return $menu;
    }
}