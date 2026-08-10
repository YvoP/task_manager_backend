<?php

namespace App\DataFixtures;

use App\Factory\ChatFactory;
use App\Factory\FileFactory;
use App\Factory\MeetingFactory;
use App\Factory\MessageFactory;
use App\Factory\ProjectFactory;
use App\Factory\ProjectUserFactory;
use App\Factory\TaskContentFactory;
use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        /*
         * ------------------------------------------------------------------
         * Users
         * ------------------------------------------------------------------
         */

        UserFactory::createOne([
            'username' => 'admin',
            'email' => 'admin@admin.fr',
            'password' => '123456',
            'roles' => ['ROLE_ADMIN'],
            'createdAt' => new \DateTimeImmutable(),
        ]);

        echo 'Creating Users';

        $users = UserFactory::createMany(15);

        /*
         * ------------------------------------------------------------------
         * Projects
         * ------------------------------------------------------------------
         */

        echo 'Creating Pprojects';

        $projects = ProjectFactory::createMany(12);

        foreach ($projects as $project) {

            /*
             * --------------------------------------------------------------
             * Team
             * --------------------------------------------------------------
             */

            echo 'Creating Teams';

            $team = [];

            foreach ($faker->randomElements($users, $faker->numberBetween(4, 8)) as $user) {

                $team[] = ProjectUserFactory::createOne([
                    'user' => $user,
                    'project' => $project,
                    'joinedAt' => \DateTimeImmutable::createFromMutable(
                        $faker->dateTimeBetween('-1 year')
                    ),
                ]);
            }

            /*
             * --------------------------------------------------------------
             * Chat
             * --------------------------------------------------------------
             */

            echo 'Creating Chats';

            $chat = ChatFactory::createOne([
                'project' => $project,
            ]);

            /*
             * --------------------------------------------------------------
             * Messages
             * --------------------------------------------------------------
             */

            echo 'Creating Messages';

            $currentDate = $faker->dateTimeBetween('-6 months');

            foreach (range(1, $faker->numberBetween(80, 150)) as $i) {

                $currentDate = $faker->dateTimeBetween(
                    $currentDate,
                    'now'
                );

                MessageFactory::createOne([
                    'chat' => $chat,
                    'projectUser' => $faker->randomElement($team),
                    'createdAt' => \DateTimeImmutable::createFromMutable($currentDate),
                ]);
            }

            /*
             * --------------------------------------------------------------
             * Meetings
             * --------------------------------------------------------------
             */

            echo 'Creating Meetings';

            $meetings = [];

            foreach (range(1, $faker->numberBetween(2, 5)) as $i) {

                $meeting = MeetingFactory::createOne([
                    'project' => $project,
                    'createdAt' => \DateTimeImmutable::createFromMutable(
                        $faker->dateTimeBetween('-6 months')
                    ),
                ]);

                $meetings[] = $meeting;

                foreach ($team as $member) {
                    $meeting->addProjectUser($member);
                }
            }

            /*
             * --------------------------------------------------------------
             * Tasks
             * --------------------------------------------------------------
             */

            echo 'Creating Tasks';

            foreach (range(1, $faker->numberBetween(15, 30)) as $i) {

                $creator = $faker->randomElement($team);

                $task = TaskFactory::createOne([
                    'project' => $project,
                    'createdBy' => $creator,
                    'sourceMeeting' => $faker->optional(0.35)->randomElement($meetings),
                    'createdAt' => \DateTimeImmutable::createFromMutable(
                        $faker->dateTimeBetween('-5 months')
                    ),
                ]);

                $modifier = $creator;
                $assignee = $faker->optional(0.8)->randomElement($team);

                $statuses = [
                    'TODO',
                    'IN_PROGRESS',
                    'DONE',
                ];

                $historyLength = $faker->numberBetween(1, 4);

                $updatedAt = $task->getCreatedAt()->modify('+1 hour');

                $currentContent = null;

                for ($h = 0; $h < $historyLength; $h++) {

                    $content = TaskContentFactory::createOne([
                        'task' => $task,
                        'modifiedBy' => $modifier,
                        'attributedTo' => $assignee,
                        'updatedAt' => $updatedAt,
                        'status' => $statuses[min($h, count($statuses) - 1)],
                    ]);

                    /*
                     * Attach files
                     */
                    foreach (range(1, $faker->numberBetween(0, 2)) as $j) {

                        if ($faker->boolean(40)) {

                            $file = FileFactory::createOne([
                                'createdBy' => $modifier,
                            ]);

                            $content->addFile($file);
                        }
                    }

                    // Keep the newest content as current
                    $currentContent = $content;

                    $modifier = $faker->randomElement($team);

                    $updatedAt = $updatedAt->modify(
                        '+' . $faker->numberBetween(1, 15) . ' days'
                    );
                }

                // Set the current version
                $task->setCurrentContent($currentContent);
            }
        }

        $manager->flush();
    }
}
