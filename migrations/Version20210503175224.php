<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503175224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_response_quiz_answer (response_answer_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_7C10282484305360 (response_answer_id), UNIQUE INDEX UNIQ_7C102824AA334807 (answer_id), PRIMARY KEY(response_answer_id, answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_response_quiz_answer ADD CONSTRAINT FK_7C10282484305360 FOREIGN KEY (response_answer_id) REFERENCES app_response_answer (id)');
        $this->addSql('ALTER TABLE app_response_quiz_answer ADD CONSTRAINT FK_7C102824AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id)');
        $this->addSql('DROP TABLE reponse_quiz_answer');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reponse_quiz_answer (response_answer_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_3E328F7984305360 (response_answer_id), UNIQUE INDEX UNIQ_3E328F79AA334807 (answer_id), PRIMARY KEY(response_answer_id, answer_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reponse_quiz_answer ADD CONSTRAINT FK_3E328F7984305360 FOREIGN KEY (response_answer_id) REFERENCES app_response_answer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reponse_quiz_answer ADD CONSTRAINT FK_3E328F79AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE app_response_quiz_answer');
    }
}
