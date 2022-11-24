<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124005925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE species_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE planet (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE species (id INT NOT NULL, categories_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A50FF712A21214B7 ON species (categories_id)');
        $this->addSql('CREATE TABLE species_planet (species_id INT NOT NULL, planet_id INT NOT NULL, PRIMARY KEY(species_id, planet_id))');
        $this->addSql('CREATE INDEX IDX_91135786B2A1D860 ON species_planet (species_id)');
        $this->addSql('CREATE INDEX IDX_91135786A25E9820 ON species_planet (planet_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF712A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE species_planet ADD CONSTRAINT FK_91135786B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE species_planet ADD CONSTRAINT FK_91135786A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE species_id_seq CASCADE');
        $this->addSql('ALTER TABLE species DROP CONSTRAINT FK_A50FF712A21214B7');
        $this->addSql('ALTER TABLE species_planet DROP CONSTRAINT FK_91135786B2A1D860');
        $this->addSql('ALTER TABLE species_planet DROP CONSTRAINT FK_91135786A25E9820');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE species_planet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
