<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310222522 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intra_events CHANGE news_type news_type VARCHAR(255) DEFAULT \'1\' NOT NULL COMMENT \'1-news, 2-wydarzenie, 3-urlop\', CHANGE news_allday news_allday INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE intra_user CHANGE user_active user_active INT NOT NULL, CHANGE user_vacation_days user_vacation_days INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC08CE2248CA3048 ON intra_user (user_login)');
        $this->addSql('ALTER TABLE intra_user_position_link RENAME INDEX id TO user_position_idx');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intra_events CHANGE news_type news_type TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'1-news, 2-wydarzenie, 3-urlop\', CHANGE news_allday news_allday TINYINT(1) DEFAULT \'1\'');
        $this->addSql('DROP INDEX UNIQ_DC08CE2248CA3048 ON intra_user');
        $this->addSql('ALTER TABLE intra_user CHANGE user_active user_active TINYINT(1) NOT NULL, CHANGE user_vacation_days user_vacation_days INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intra_user_position_link RENAME INDEX user_position_idx TO id');
    }
}
