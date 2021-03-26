<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325130441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training_user (training_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8209910ABEFD98D1 (training_id), INDEX IDX_8209910AA76ED395 (user_id), PRIMARY KEY(training_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_user ADD CONSTRAINT FK_8209910ABEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE training_user ADD CONSTRAINT FK_8209910AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE training ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_D5128A8F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D5128A8F7E3C61F9 ON training (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE training_user');
        $this->addSql('ALTER TABLE training DROP FOREIGN KEY FK_D5128A8F7E3C61F9');
        $this->addSql('DROP INDEX IDX_D5128A8F7E3C61F9 ON training');
        $this->addSql('ALTER TABLE training DROP owner_id');
    }
}
