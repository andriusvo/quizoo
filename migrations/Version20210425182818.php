<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425182818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactor response answer entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE reponse_quiz_answer (response_answer_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_3E328F7984305360 (response_answer_id), UNIQUE INDEX UNIQ_3E328F79AA334807 (answer_id), PRIMARY KEY(response_answer_id, answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE reponse_quiz_answer ADD CONSTRAINT FK_3E328F7984305360 FOREIGN KEY (response_answer_id) REFERENCES app_response_answer (id)'
        );
        $this->addSql(
            'ALTER TABLE reponse_quiz_answer ADD CONSTRAINT FK_3E328F79AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id)'
        );
        $this->addSql('ALTER TABLE app_response_answer DROP FOREIGN KEY FK_EB7C4883AA334807');
        $this->addSql('DROP INDEX IDX_EB7C4883AA334807 ON app_response_answer');
        $this->addSql('DROP INDEX idx_response_answer_uniq ON app_response_answer');
        $this->addSql('ALTER TABLE app_response_answer DROP answer_id');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE reponse_quiz_answer');
        $this->addSql('ALTER TABLE app_response_answer ADD answer_id INT NOT NULL');
        $this->addSql(
            'ALTER TABLE app_response_answer ADD CONSTRAINT FK_EB7C4883AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id) ON UPDATE NO ACTION ON DELETE NO ACTION'
        );
        $this->addSql('CREATE INDEX IDX_EB7C4883AA334807 ON app_response_answer (answer_id)');
        $this->addSql('CREATE UNIQUE INDEX idx_response_answer_uniq ON app_response_answer (answer_id, response_id)');
    }
}
