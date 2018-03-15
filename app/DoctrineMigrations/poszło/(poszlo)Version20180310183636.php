<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310183636 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
//TINYINT(1)
        $this->addSql('ALTER TABLE intra_department CHANGE department_status department_status VARCHAR(255) NOT NULL');
//TINYINT(1)
        $this->addSql('ALTER TABLE intra_division CHANGE division_status division_status INT NOT NULL');
//TINYINT(1)
        $this->addSql('ALTER TABLE intra_division_position CHANGE position_status position_status INT NOT NULL');
//w bazie nie ma: id primary key
        $this->addSql('ALTER TABLE intra_division_position_link ADD id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DFCCB96DF6CA6684 ON intra_division_position_link (division_position_link_pid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DFCCB96DEDE56D28 ON intra_division_position_link (division_position_link_did)');
//LONGTEXT,utf8_unicode_ci
        $this->addSql('ALTER TABLE intra_documents CHANGE document_desc document_desc LONGTEXT NOT NULL');
//1 utf8_unicode_ci
//2 tinyint
        $this->addSql('ALTER TABLE intra_events CHANGE news_url news_url VARCHAR(127) NOT NULL, CHANGE news_type news_type VARCHAR(255) DEFAULT \'1\' NOT NULL COMMENT \'1-news, 2-wydarzenie, 3-urlop\', CHANGE news_allday news_allday INT DEFAULT 1 NOT NULL');
//TINYINT(1)
//"default":"NULL"
        $this->addSql('ALTER TABLE intra_user CHANGE user_active user_active INT NOT NULL, CHANGE user_vacation_days user_vacation_days INT DEFAULT NULL');
//index
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC08CE2248CA3048 ON intra_user (user_login)');
        $this->addSql('ALTER TABLE intra_user_position_link RENAME INDEX id TO user_position_idx');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intra_department CHANGE department_status department_status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE intra_division CHANGE division_status division_status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE intra_division_position CHANGE position_status position_status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE intra_division_position_link MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_DFCCB96DF6CA6684 ON intra_division_position_link');
        $this->addSql('DROP INDEX UNIQ_DFCCB96DEDE56D28 ON intra_division_position_link');
        $this->addSql('ALTER TABLE intra_division_position_link DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE intra_division_position_link DROP id');
        $this->addSql('ALTER TABLE intra_documents CHANGE document_desc document_desc TEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE intra_events CHANGE news_url news_url VARCHAR(127) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE news_type news_type TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'1-news, 2-wydarzenie, 3-urlop\', CHANGE news_allday news_allday TINYINT(1) DEFAULT \'1\'');
        $this->addSql('DROP INDEX UNIQ_DC08CE2248CA3048 ON intra_user');
        $this->addSql('ALTER TABLE intra_user CHANGE user_active user_active TINYINT(1) NOT NULL, CHANGE user_vacation_days user_vacation_days INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intra_user_position_link RENAME INDEX user_position_idx TO id');
    }
}
