<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531232249 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nines_dc_value (id INT AUTO_INCREMENT NOT NULL, element_id INT DEFAULT NULL, data VARCHAR(255) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', entity VARCHAR(120) NOT NULL, INDEX IDX_879CABBA1F1F2A24 (element_id), FULLTEXT INDEX nines_dc_value_ft (data), INDEX nines_dc_value_entity (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nines_dc_value ADD CONSTRAINT FK_879CABBA1F1F2A24 FOREIGN KEY (element_id) REFERENCES nines_dc_element (id)');
        $this->addSql('ALTER TABLE artwork_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE artwork_role CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE media_file_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE media_file_field ADD CONSTRAINT FK_FB533BFB1F1F2A24 FOREIGN KEY (element_id) REFERENCES nines_dc_element (id)');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_23FD24C7A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX idx_f4da3ab0a76ed395 TO IDX_23FD24C7A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX blog_page_content TO blog_page_ft');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6AA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_ba5ae01d12469de2 TO IDX_6D7DFE6A12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_ba5ae01d6bf700bd TO IDX_6D7DFE6A6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_ba5ae01da76ed395 TO IDX_6D7DFE6AA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX blog_post_content TO blog_post_ft');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_ca275a0cea750e8 TO IDX_32F5FC8CEA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_ca275a0c6de44026 TO IDX_32F5FC8C6DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_ca275a0cea750e86de44026 TO IDX_32F5FC8CEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX uniq_ca275a0c5e237e06 TO UNIQ_32F5FC8C5E237E06');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_92121d87ea750e8 TO IDX_4A63E2FDEA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_92121d876de44026 TO IDX_4A63E2FD6DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_92121d87ea750e86de44026 TO IDX_4A63E2FDEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX uniq_92121d875e237e06 TO UNIQ_4A63E2FD5E237E06');
        $this->addSql('ALTER TABLE nines_dc_element CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX idx_41405e39ea750e8 TO IDX_C0F4D2B1EA750E8');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX idx_41405e396de44026 TO IDX_C0F4D2B16DE44026');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX idx_41405e39ea750e86de44026 TO IDX_C0F4D2B1EA750E86DE44026');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX uniq_41405e39841cb121 TO UNIQ_C0F4D2B1841CB121');
        $this->addSql('ALTER TABLE project_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE project_role CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE venue_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nines_dc_value DROP FOREIGN KEY FK_879CABBA1F1F2A24');
        $this->addSql('DROP TABLE nines_dc_value');
        $this->addSql('ALTER TABLE artwork_role CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6AA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX blog_post_ft TO blog_post_content');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_6d7dfe6a12469de2 TO IDX_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_6d7dfe6a6bf700bd TO IDX_BA5AE01D6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_6d7dfe6aa76ed395 TO IDX_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE media_file_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_32f5fc8c6de44026 TO IDX_CA275A0C6DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_32f5fc8cea750e86de44026 TO IDX_CA275A0CEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX uniq_32f5fc8c5e237e06 TO UNIQ_CA275A0C5E237E06');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_32f5fc8cea750e8 TO IDX_CA275A0CEA750E8');
        $this->addSql('ALTER TABLE project_role CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_dc_element CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX idx_c0f4d2b1ea750e86de44026 TO IDX_41405E39EA750E86DE44026');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX uniq_c0f4d2b1841cb121 TO UNIQ_41405E39841CB121');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX idx_c0f4d2b1ea750e8 TO IDX_41405E39EA750E8');
        $this->addSql('ALTER TABLE nines_dc_element RENAME INDEX idx_c0f4d2b16de44026 TO IDX_41405E396DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_4a63e2fdea750e86de44026 TO IDX_92121D87EA750E86DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX uniq_4a63e2fd5e237e06 TO UNIQ_92121D875E237E06');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_4a63e2fdea750e8 TO IDX_92121D87EA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_4a63e2fd6de44026 TO IDX_92121D876DE44026');
        $this->addSql('ALTER TABLE media_file_field DROP FOREIGN KEY FK_FB533BFB1F1F2A24');
        $this->addSql('ALTER TABLE venue_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE project_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE artwork_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_23FD24C7A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX idx_23fd24c7a76ed395 TO IDX_F4DA3AB0A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX blog_page_ft TO blog_page_content');
    }
}
