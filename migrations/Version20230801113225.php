<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801113225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horaire (id INT AUTO_INCREMENT NOT NULL, time_sheet_id INT DEFAULT NULL, date VARCHAR(50) DEFAULT NULL, in_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', out_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', break_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_time INT DEFAULT NULL, total_break INT DEFAULT NULL, INDEX IDX_BBC83DB62F974569 (time_sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_sheet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, year INT DEFAULT NULL, start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', daily_hours INT DEFAULT NULL, weekend TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uptated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C24E709EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horaire ADD CONSTRAINT FK_BBC83DB62F974569 FOREIGN KEY (time_sheet_id) REFERENCES time_sheet (id)');
        $this->addSql('ALTER TABLE time_sheet ADD CONSTRAINT FK_C24E709EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horaire DROP FOREIGN KEY FK_BBC83DB62F974569');
        $this->addSql('ALTER TABLE time_sheet DROP FOREIGN KEY FK_C24E709EA76ED395');
        $this->addSql('DROP TABLE horaire');
        $this->addSql('DROP TABLE time_sheet');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
