<?php

namespace App\Command;

ini_set('memory_limit', '-1');

use App\Entity\Account;
use App\Entity\Bill;
use App\Entity\Communication;
use App\Entity\Subscriber;
use App\Entity\Type;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    private const BILLED_ACCOUNT = 0;
    private const BILL_NUMBER = 1;
    private const SUBSCRIBER_NUMBER = 2;
    private const DATE = 3;
    private const TIME = 4;
    private const REAL_DURATION = 5;
    private const BILLED_DURATION = 6;
    private const TYPE = 7;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-data';

    protected function configure(): void
    {
        // the short description shown while running "php bin/console list"
        $this->setDescription('Import CSV data ');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (($handle = fopen(__DIR__ . "/files/tickets_appels_201202.csv", "r")) !== FALSE) {

            $i = 0;
            ini_set('auto_detect_line_endings',TRUE);

            while (($data = fgetcsv($handle, 200, ";")) !== FALSE) {
                if (preg_match('/^[0-9]{8}$/', $data[self::BILLED_ACCOUNT])) {
                    $subscriber = $this->handleSubscriber(trim($data[self::SUBSCRIBER_NUMBER]));
                    $account = $this->handleAccount(trim($data[self::BILLED_ACCOUNT]));
                    $subscriber->addAccount($account);

                    $bill = $this->handleBill(trim($data[self::BILL_NUMBER]));
                    $account->addBill($bill);

                    $type = $this->handleType(trim($data[self::TYPE]));

                    echo $data[self::TYPE].PHP_EOL;

                    $this->handleCommunication(
                        $type,
                        $bill,
                        trim($data[self::DATE]),
                        trim($data[self::TIME]),
                        trim($data[self::REAL_DURATION]),
                        trim($data[self::BILLED_DURATION])
                    );
                    $this->em->flush();
                    unset($data);
                    ++$i;
                    if($i>=100)
                    {
                        $i = 0;
                        $this->em->clear();
                    }
                }
            }
            fclose($handle);
            ini_set('auto_detect_line_endings',FALSE);
            $this->em->flush();

            return Command::SUCCESS;
        }
        return Command::FAILURE;

    }

    private function handleSubscriber(string $numberSubscriber): Subscriber
    {
        $subscriber = $this->em->getRepository(Subscriber::class)->findOneBy(['number'=>$numberSubscriber]);

        if(!$subscriber instanceof Subscriber)
        {
            $subscriber = new Subscriber();
            $subscriber->setNumber($numberSubscriber);

            $this->em->persist($subscriber);
        }
        return $subscriber;
    }

    private function handleAccount(string $accountNumber): Account
    {
        $account = $this->em->getRepository(Account::class)->findOneBy(['number'=>$accountNumber]);

        if (!$account instanceof Account)
        {
            $account = new Account();
            $account->setNumber($accountNumber);

            $this->em->persist($account);
        }
        return $account;
    }

    private function handleBill(string $billNumber): Bill
    {
        $bill = $this->em->getRepository(Bill::class)->findOneBy(['number'=>$billNumber]);

        if (!$bill instanceof Bill)
        {
            $bill = new Bill();
            $bill->setNumber($billNumber);

            $this->em->persist($bill);
        }
        return $bill;
    }

    private function handleType(string $name): Type
    {
        $type = $this->em->getRepository(Type::class)->findOneBy(['name'=>$name]);

        if (!$type instanceof Type)
        {
            $type = new Type();
            $type->setName($name);

            $this->em->persist($type);
        }
        return $type;
    }

    private function handleCommunication(
        Type $type,
        Bill $bill,
        string $date,
        string $time,
        string $realDuration,
        string $billedDuration
    ): Communication {
        $communication = new Communication();
        $communication->setType($type);

        if($this->validateDate('d/m/Y', $date))
        {
            $communication->setDate(DateTime::createFromFormat('d/m/Y', $date));
        } else
        {
            throw new \Exception('bad date format');
        }

        if($this->validateDate('H:i:s', $time))
        {
            $communication->setTime(DateTime::createFromFormat('H:i:s', $time));
        } else
        {
            throw new \Exception('bad date format');
        }

        $communication->setRealDuration(
            $this->validateDate('H:i:s', $realDuration) ?
                DateTime::createFromFormat('H:i:s', $realDuration) :
                null
        );

        $communication->setBilledDuration(
            $this->validateDate('H:i:s', $billedDuration) ?
                DateTime::createFromFormat('H:i:s', $billedDuration) :
                null
        );

        $bill->addCommunication($communication);

        $this->em->persist($communication);

        return $communication;
    }

    private function validateDate(string $format, string $date): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * @param EntityManagerInterface $em
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }
}
