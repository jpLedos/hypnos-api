<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321142218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE suite (id INT AUTO_INCREMENT NOT NULL, highlight_picture_id INT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, booking_link VARCHAR(255) DEFAULT NULL, price INT NOT NULL, INDEX IDX_153CE4264DDF06DE (highlight_picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suite_picture (suite_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_71DD98054FFCB518 (suite_id), INDEX IDX_71DD9805EE45BDBF (picture_id), PRIMARY KEY(suite_id, picture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suite ADD CONSTRAINT FK_153CE4264DDF06DE FOREIGN KEY (highlight_picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE suite_picture ADD CONSTRAINT FK_71DD98054FFCB518 FOREIGN KEY (suite_id) REFERENCES suite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suite_picture ADD CONSTRAINT FK_71DD9805EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD suite_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554FFCB518 FOREIGN KEY (suite_id) REFERENCES suite (id)');
        $this->addSql('CREATE INDEX IDX_42C849554FFCB518 ON reservation (suite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554FFCB518');
        $this->addSql('ALTER TABLE suite_picture DROP FOREIGN KEY FK_71DD98054FFCB518');
        $this->addSql('DROP TABLE suite');
        $this->addSql('DROP TABLE suite_picture');
        $this->addSql('ALTER TABLE hotel CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE picture CHANGE picture picture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE short_description short_description VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_42C849554FFCB518 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP suite_id');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
