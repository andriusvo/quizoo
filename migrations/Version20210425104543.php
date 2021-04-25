<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425104543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop finished flag';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_quiz DROP finished');
        $this->addSql('ALTER TABLE app_subject RENAME INDEX idx_1693be37a76ed395 TO IDX_1693BE3719E9AC5F');
        $this->addSql('ALTER TABLE app_quiz ADD manual_evaluation TINYINT(1) NOT NULL, ADD enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_quiz ADD finished TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE app_subject RENAME INDEX idx_1693be3719e9ac5f TO IDX_1693BE37A76ED395');
        $this->addSql('ALTER TABLE app_quiz DROP manual_evaluation, DROP enabled');
    }
}
