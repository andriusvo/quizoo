<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210506161902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changed fields to nullable, added relationship';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_response CHANGE start_date start_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE app_response_answer ADD question_id INT NOT NULL');
        $this->addSql(
            'ALTER TABLE app_response_answer ADD CONSTRAINT FK_EB7C48831E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id)'
        );
        $this->addSql('CREATE INDEX IDX_EB7C48831E27F6BF ON app_response_answer (question_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_response CHANGE start_date start_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE app_response_answer DROP FOREIGN KEY FK_EB7C48831E27F6BF');
        $this->addSql('DROP INDEX IDX_EB7C48831E27F6BF ON app_response_answer');
        $this->addSql('ALTER TABLE app_response_answer DROP question_id');
    }
}
