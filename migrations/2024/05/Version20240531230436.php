<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531230436 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_file_field DROP FOREIGN KEY FK_FB533BFB1F1F2A24');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D6BF700BD');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE comment_note DROP FOREIGN KEY FK_E98B58F8A76ED395');
        $this->addSql('ALTER TABLE comment_note DROP FOREIGN KEY FK_E98B58F8F8697D13');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6BF700BD');
        $this->addSql('ALTER TABLE blog_page DROP FOREIGN KEY FK_F4DA3AB0A76ED395');

        $this->addSql('RENAME TABLE blog_post_category to nines_blog_post_category');
        $this->addSql('RENAME TABLE blog_post to nines_blog_post');
        $this->addSql('RENAME TABLE blog_post_status to nines_blog_post_status');
        $this->addSql('RENAME TABLE blog_page to nines_blog_page');
        $this->addSql('RENAME TABLE element to nines_dc_element');

        $this->addSql('DROP TABLE comment_note');
        $this->addSql('DROP TABLE comment_status');
        $this->addSql('DROP TABLE comment');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_note (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comment_id INT NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E98B58F8A76ED395 (user_id), INDEX IDX_E98B58F8F8697D13 (comment_id), FULLTEXT INDEX commentnote_ft_idx (content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comment_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, label VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', FULLTEXT INDEX IDX_B1133D0EEA750E86DE44026 (label, description), UNIQUE INDEX UNIQ_B1133D0E5E237E06 (name), FULLTEXT INDEX IDX_B1133D0EEA750E8 (label), FULLTEXT INDEX IDX_B1133D0E6DE44026 (description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, fullname VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, follow_up TINYINT(1) NOT NULL, entity VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C6BF700BD (status_id), FULLTEXT INDEX comment_ft_idx (fullname, content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment_note ADD CONSTRAINT FK_E98B58F8A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE comment_note ADD CONSTRAINT FK_E98B58F8F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6BF700BD FOREIGN KEY (status_id) REFERENCES comment_status (id)');

        $this->addSql('RENAME TABLE nines_blog_post_category to blog_post_category');
        $this->addSql('RENAME TABLE nines_blog_post to blog_post');
        $this->addSql('RENAME TABLE nines_blog_post_status to blog_post_status');
        $this->addSql('RENAME TABLE nines_blog_page to blog_page');
        $this->addSql('RENAME TABLE nines_dc_element to element');
    }
}
