<?php
    namespace StudentBundle\Manager;
    use StudentBundle\Entity\User;
    use StudentBundle\Entity\Role;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Validator\Constraints\Email;

 class UserManager {

    protected $em;
    protected $container;
    protected $data = array(
        'status' => FALSE,
        'message' => '',
        'data' => array(),
    );
//    protected $router;
//    protected $UserRepository;
     /**
     * Construct
     *
     * @param type $em
     * @param type $container
     * @param type $router
     */
    public function __construct($container)
    {
        $this->em = $em;
        $this->container = $container;
//        $this->router = $router;
//        $this->UserRepository = $this->em->getRepository("StudentBundle:User");
    }
     /**
     * Create encode password
     *
     * @param \StudentBundle\Entity\User $entity
     * @param string                     $password
     *
     * @return type
     */
    function createEncodePassword($entity, $password)
    {
        $encoder = $this->container->get('security.encoder_factory')
                ->getEncoder($entity);
        $password = $encoder->encodePassword($password, '');

        return $password;
    }
    function isEmail ($email){
        $emailConstraint = new Email();
        $emailConstraint->message = 'Invalid email address';
        $errorList = $this->container->get('validator')->validateValue($email, $emailConstraint);
        if (count($errorList) == 0) {
            $data = array('success' => true, 'error' => 'successali');
        } else {
            $data = array('success' => false, 'error' => $errorList[0]->getMessage());
        }
        return $data;
    }
    /**
     * Process login for user
     *
     * @param type $username
     * @param type $password
     * @param type $deviceID
     * @param type $deviceToken
     *
     * @return Array
     */
    public function userLogin($username, $password)
    {
        $data = $this->data;
        $em = $this->em;

        $repoUser = $em->getRepository('StudentBundle:User');

        $user = $repoUser->findOneBy(array('username' => $username));

        if ($user === null) {
            $data['message'] = $this->translator->trans(
                'Username not exist', array(), 'messages'
            );
        } else {

            $loginPasswordEncode = $this->createEncodePassword($user, $password);

            if ($loginPasswordEncode === $user->getPassword()) {

                $data['status'] = TRUE;
                $data['message'] = '';
            } else {
                $data['message'] = 'Password incorrect';
            }
        }

        return $data;
    }
 }
