<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218083227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden CHANGE fecha_pago fecha_pago DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE producto CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE fotos fotos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles JSON NOT NULL, CHANGE foto foto VARCHAR(255) DEFAULT NULL, CHANGE ultima_fecha_acceso ultima_fecha_acceso DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden CHANGE fecha_pago fecha_pago DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE producto CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE fotos fotos LONGTEXT CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE usuario CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE foto foto VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ultima_fecha_acceso ultima_fecha_acceso DATE DEFAULT \'NULL\'');
    }
}
