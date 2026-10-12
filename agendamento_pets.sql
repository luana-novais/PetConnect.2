CREATE TABLE agendamento_pets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_agendamento BIGINT UNSIGNED NOT NULL,
    id_pet BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_agendamento_pets_agendamento
        FOREIGN KEY (id_agendamento) REFERENCES agendamentos(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_agendamento_pets_pet
        FOREIGN KEY (id_pet) REFERENCES pets(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    UNIQUE KEY unique_agendamento_pet (id_agendamento, id_pet)
);
