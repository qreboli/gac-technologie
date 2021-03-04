<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304113325 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_bill (account_id INT NOT NULL, bill_id INT NOT NULL, INDEX IDX_F6B29E499B6B5FBA (account_id), INDEX IDX_F6B29E491A8C12F5 (bill_id), PRIMARY KEY(account_id, bill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bill (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, time TIME NOT NULL, date DATE NOT NULL, real_duration TIME DEFAULT NULL, billed_duration TIME DEFAULT NULL, INDEX IDX_F9AFB5EBC54C8C93 (type_id), INDEX IDX_F9AFB5EB1A8C12F5 (bill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriber (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriber_account (subscriber_id INT NOT NULL, account_id INT NOT NULL, INDEX IDX_6FBFEA237808B1AD (subscriber_id), INDEX IDX_6FBFEA239B6B5FBA (account_id), PRIMARY KEY(subscriber_id, account_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account_bill ADD CONSTRAINT FK_F6B29E499B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE account_bill ADD CONSTRAINT FK_F6B29E491A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EBC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB1A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE subscriber_account ADD CONSTRAINT FK_6FBFEA237808B1AD FOREIGN KEY (subscriber_id) REFERENCES subscriber (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscriber_account ADD CONSTRAINT FK_6FBFEA239B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_bill DROP FOREIGN KEY FK_F6B29E499B6B5FBA');
        $this->addSql('ALTER TABLE subscriber_account DROP FOREIGN KEY FK_6FBFEA239B6B5FBA');
        $this->addSql('ALTER TABLE account_bill DROP FOREIGN KEY FK_F6B29E491A8C12F5');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EB1A8C12F5');
        $this->addSql('ALTER TABLE subscriber_account DROP FOREIGN KEY FK_6FBFEA237808B1AD');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EBC54C8C93');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE account_bill');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE communication');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('DROP TABLE subscriber_account');
        $this->addSql('DROP TABLE type');
    }
}
