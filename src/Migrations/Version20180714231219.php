<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180714231219 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cabinet (id INT AUTO_INCREMENT NOT NULL, id_expert_id INT NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, contact VARCHAR(30) NOT NULL, INDEX IDX_4CED05B0456330C3 (id_expert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expert (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, contact VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cabinet ADD CONSTRAINT FK_4CED05B0456330C3 FOREIGN KEY (id_expert_id) REFERENCES expert (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cabinet DROP FOREIGN KEY FK_4CED05B0456330C3');
        $this->addSql('DROP TABLE cabinet');
        $this->addSql('DROP TABLE expert');
    }
}
