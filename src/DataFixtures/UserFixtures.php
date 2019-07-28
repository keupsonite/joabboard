<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_REFERENCE = 'user-admin';
    public const RECRUITER_REFERENCE = 'user-recruiter0';
    public const RECRUITER2_REFERENCE = 'user-recruiter1';
    public const CANDIDATE_REFERENCE = 'user-candidate';

    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = [
            [
                '_reference' => self::ADMIN_REFERENCE,
                'email' => 'root@joabboard.com',
                'password' => '123',
                'roles' => [
                    'ROLE_SUPER_ADMIN',
                ],
            ],
            [
                '_reference' => self::RECRUITER_REFERENCE,
                'email' => 'recruiter@joabboard.com',
                'password' => 'password',
                'roles' => [
                    'ROLE_RECRUITER',
                ],
            ],
            [
                '_reference' => self::RECRUITER2_REFERENCE,
                'email' => 'recruiter2@joabboard.com',
                'password' => 'password',
                'roles' => [
                    'ROLE_RECRUITER',
                ],
            ],
            [
                '_reference' => self::CANDIDATE_REFERENCE,
                'email' => 'candidate@joabboard.com',
                'password' => 'password',
                'roles' => [
                    'ROLE_CANDIDATE',
                ],
            ],
        ];

        foreach ($users as $user) {
            $object = new User();
            $object
                ->setEmail($user['email'])
                ->setPassword($this->passwordEncoder->encodePassword($object, $user['password']))
                ->setRoles($user['roles'])
            ;

            $manager->persist($object);
            $manager->flush();

            $this->addReference($user['_reference'], $object);
        }
    }
}
