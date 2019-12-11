<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208175004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE episode ADD season_id_id INT DEFAULT NULL, ADD title VARCHAR(255) NOT NULL, ADD number INT NOT NULL, ADD synopsis LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA68756988 FOREIGN KEY (season_id_id) REFERENCES season (id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA68756988 ON episode (season_id_id)');
        $this->addSql('ALTER TABLE season ADD program_id INT NOT NULL, ADD number INT NOT NULL, ADD year INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9E12DEDA1 FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('CREATE INDEX IDX_F0E45BA9E12DEDA1 ON season (program_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA68756988');
        $this->addSql('DROP INDEX IDX_DDAA1CDA68756988 ON episode');
        $this->addSql('ALTER TABLE episode DROP season_id_id, DROP title, DROP number, DROP synopsis');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA9E12DEDA1');
        $this->addSql('DROP INDEX IDX_F0E45BA9E12DEDA1 ON season');
        $this->addSql('ALTER TABLE season DROP program_id, DROP number, DROP year, DROP description');
    }
}
