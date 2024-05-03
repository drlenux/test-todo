<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502054816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F26E5D72');
        $this->addSql('DROP INDEX IDX_527EDB25F26E5D72 ON task');
        $this->addSql('ALTER TABLE task CHANGE sub_task_id parent_task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25FFFE75C0 FOREIGN KEY (parent_task_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25FFFE75C0 ON task (parent_task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25FFFE75C0');
        $this->addSql('DROP INDEX IDX_527EDB25FFFE75C0 ON task');
        $this->addSql('ALTER TABLE task CHANGE parent_task_id sub_task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F26E5D72 FOREIGN KEY (sub_task_id) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_527EDB25F26E5D72 ON task (sub_task_id)');
    }
}
