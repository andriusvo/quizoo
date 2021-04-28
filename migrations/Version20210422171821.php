<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210422171821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Quiz & Questions & Answers entities';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE app_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, value VARCHAR(255) NOT NULL, correct TINYINT(1) NOT NULL, INDEX IDX_3FDE27A51E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE app_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, type VARCHAR(50) NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_BE7729E3853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE app_quiz (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, valid_from DATETIME NOT NULL, valid_to DATETIME NOT NULL, code VARCHAR(30) NOT NULL, title VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, finished TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A13CDF3277153098 (code), INDEX IDX_A13CDF32A76ED395 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE app_answer ADD CONSTRAINT FK_3FDE27A51E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id)'
        );
        $this->addSql(
            'ALTER TABLE app_question ADD CONSTRAINT FK_BE7729E3853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id)'
        );
        $this->addSql(
            'ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF32A76ED395 FOREIGN KEY (owner_id) REFERENCES platform_admin_user (id)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_answer DROP FOREIGN KEY FK_3FDE27A51E27F6BF');
        $this->addSql('ALTER TABLE app_question DROP FOREIGN KEY FK_BE7729E3853CD175');
        $this->addSql('DROP TABLE app_answer');
        $this->addSql('DROP TABLE app_question');
        $this->addSql('DROP TABLE app_quiz');
    }
}
