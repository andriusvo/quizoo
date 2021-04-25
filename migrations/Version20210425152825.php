<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425152825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Response entity added';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE app_response (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, student_id INT NOT NULL, score INT DEFAULT NULL, start_date DATETIME NOT NULL, finish_date DATETIME DEFAULT NULL, INDEX IDX_36FB6B56853CD175 (quiz_id), INDEX IDX_36FB6B56CB944F1A (student_id), UNIQUE INDEX idx_student_quiz_uniq (student_id, quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE app_response_answer (id INT AUTO_INCREMENT NOT NULL, answer_id INT NOT NULL, response_id INT NOT NULL, correct TINYINT(1) NOT NULL, INDEX IDX_EB7C4883AA334807 (answer_id), INDEX IDX_EB7C4883FBF32840 (response_id), UNIQUE INDEX idx_response_answer_uniq (answer_id, response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE app_response ADD CONSTRAINT FK_36FB6B56853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id)'
        );
        $this->addSql(
            'ALTER TABLE app_response ADD CONSTRAINT FK_36FB6B56CB944F1A FOREIGN KEY (student_id) REFERENCES app_user (id)'
        );
        $this->addSql(
            'ALTER TABLE app_response_answer ADD CONSTRAINT FK_EB7C4883AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id)'
        );
        $this->addSql(
            'ALTER TABLE app_response_answer ADD CONSTRAINT FK_EB7C4883FBF32840 FOREIGN KEY (response_id) REFERENCES app_response (id)'
        );
        $this->addSql('ALTER TABLE app_quiz DROP manual_evaluation');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_response_answer DROP FOREIGN KEY FK_EB7C4883FBF32840');
        $this->addSql('DROP TABLE app_response');
        $this->addSql('DROP TABLE app_response_answer');
        $this->addSql('ALTER TABLE app_quiz ADD manual_evaluation TINYINT(1) NOT NULL');
    }
}
