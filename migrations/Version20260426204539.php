<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260426204539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id UUID NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tenant_id UUID NOT NULL, venue_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA740A73EBA ON event (venue_id)');
        $this->addSql('CREATE INDEX idx_event_tenant ON event (tenant_id)');
        $this->addSql('CREATE INDEX idx_event_status ON event (status)');
        $this->addSql('CREATE UNIQUE INDEX uq_event_tenant_slug ON event (tenant_id, slug)');
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, status VARCHAR(255) NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, notes TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_order_user ON "order" (user_id)');
        $this->addSql('CREATE INDEX idx_order_status ON "order" (status)');
        $this->addSql('CREATE TABLE payment (id UUID NOT NULL, provider VARCHAR(255) NOT NULL, provider_ref VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, failed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refunded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, metadata JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, order_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D8D9F6D38 ON payment (order_id)');
        $this->addSql('CREATE INDEX idx_payment_provider_ref ON payment (provider_ref)');
        $this->addSql('CREATE TABLE tenant (id UUID NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, logo_url VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4E59C462989D9B62 ON tenant (slug)');
        $this->addSql('CREATE TABLE ticket (id UUID NOT NULL, status VARCHAR(255) NOT NULL, qr_code VARCHAR(500) NOT NULL, attendee_name VARCHAR(255) DEFAULT NULL, attendee_email VARCHAR(255) DEFAULT NULL, checked_in_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, checked_in_by_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, order_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA37D8B1FB5 ON ticket (qr_code)');
        $this->addSql('CREATE INDEX idx_ticket_order ON ticket (order_id)');
        $this->addSql('CREATE INDEX idx_ticket_category ON ticket (category_id)');
        $this->addSql('CREATE INDEX idx_ticket_qr_code ON ticket (qr_code)');
        $this->addSql('CREATE TABLE ticket_category (id UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, currency VARCHAR(255) NOT NULL, total_quantity INT NOT NULL, sold_quantity INT NOT NULL, max_per_order INT NOT NULL, sale_start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sale_end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, event_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_ticket_category_event ON ticket_category (event_id)');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tenant_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_user_tenant ON "user" (tenant_id)');
        $this->addSql('CREATE UNIQUE INDEX uq_user_tenant_email ON "user" (tenant_id, email)');
        $this->addSql('CREATE TABLE venue (id UUID NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, capacity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tenant_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_venue_tenant ON venue (tenant_id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA79033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA740A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA312469DE2 FOREIGN KEY (category_id) REFERENCES ticket_category (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE ticket_category ADD CONSTRAINT FK_8325E54071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6499033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE venue ADD CONSTRAINT FK_91911B0D9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA79033212A');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA740A73EBA');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D8D9F6D38');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA38D9F6D38');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA312469DE2');
        $this->addSql('ALTER TABLE ticket_category DROP CONSTRAINT FK_8325E54071F7E88B');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6499033212A');
        $this->addSql('ALTER TABLE venue DROP CONSTRAINT FK_91911B0D9033212A');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE tenant');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_category');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE venue');
    }
}
