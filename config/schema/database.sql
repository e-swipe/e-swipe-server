-- MySQL Script generated by MySQL Workbench
-- Fri May 26 02:11:19 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

-- -----------------------------------------------------
-- Schema eswipe
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `genders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `genders`;

CREATE TABLE IF NOT EXISTS `genders` (
    `id`   INT UNSIGNED NOT NULL,
    `name` VARCHAR(50)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    UNIQUE INDEX `name_UNIQUE` (`name` ASC)
);

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT UNSIGNED NOT NULL,
    `facebook_id`   BIGINT(64)   NULL     DEFAULT NULL,
    `email`         VARCHAR(250) NOT NULL,
    `password`      VARCHAR(250) NOT NULL,
    `instance_id`   VARCHAR(250) NOT NULL,
    `firstname`     VARCHAR(250) NOT NULL,
    `lastname`      VARCHAR(250) NOT NULL,
    `description`   VARCHAR(500) NOT NULL,
    `date_of_birth` DATE         NOT NULL,
    `latitude`      DOUBLE       NULL,
    `longitude`     DOUBLE       NULL,
    `is_visible`    TINYINT      NOT NULL DEFAULT 1,
    `gender_id`     INT UNSIGNED NOT NULL,
    `min_age`       INT UNSIGNED NOT NULL     DEFAULT 18,
    `max_age`       INT UNSIGNED NOT NULL     DEFAULT 18,
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC),
    UNIQUE INDEX `facebook_id_UNIQUE` (`facebook_id` ASC),
    PRIMARY KEY (`id`),
    INDEX `fk_users_genders1_idx` (`gender_id` ASC),
    FOREIGN KEY (`gender_id`)
    REFERENCES `genders` (`id`)
);

-- -----------------------------------------------------
-- Table `chat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chat`;

CREATE TABLE IF NOT EXISTS `chat` (
    `id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC)
);

-- -----------------------------------------------------
-- Table `matches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matches`;

CREATE TABLE IF NOT EXISTS `matches` (
    `matcher_id` INT UNSIGNED NOT NULL,
    `matched_id` INT UNSIGNED NOT NULL,
    `chat_id`    INT UNSIGNED NOT NULL,
    PRIMARY KEY (`matcher_id`, `matched_id`),
    INDEX `fk_matches_users1_idx` (`matched_id` ASC),
    INDEX `fk_matches_chat1_idx` (`chat_id` ASC),
    INDEX `chat_index` (`chat_id` ASC),
    FOREIGN KEY (`matcher_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`matched_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`chat_id`)
    REFERENCES `chat` (`id`)
)
    COMMENT = 'need to be duplicated for each match :)';

-- -----------------------------------------------------
-- Table `accept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `accept`;

CREATE TABLE IF NOT EXISTS `accept` (
    `accepter_id` INT UNSIGNED NOT NULL,
    `accepted_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`accepter_id`, `accepted_id`),
    INDEX `fk_accept_users2_idx` (`accepted_id` ASC),
    FOREIGN KEY (`accepter_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`accepted_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table `decline`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `decline`;

CREATE TABLE IF NOT EXISTS `decline` (
    `decliner_id` INT UNSIGNED NOT NULL,
    `declined_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`decliner_id`, `declined_id`),
    INDEX `fk_accept_users2_idx` (`declined_id` ASC),
    FOREIGN KEY (`decliner_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`declined_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table `events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`;

CREATE TABLE IF NOT EXISTS `events` (
    `id`          INT UNSIGNED NOT NULL,
    `name`        VARCHAR(250) NOT NULL,
    `description` VARCHAR(500) NOT NULL,
    `date_begin`  DATETIME     NOT NULL,
    `date_end`    DATETIME     NOT NULL,
    `latitude`    DOUBLE       NULL,
    `longitude`   DOUBLE       NULL,
    `is_visible`  TINYINT      NOT NULL DEFAULT 1,
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    PRIMARY KEY (`id`)
);

-- -----------------------------------------------------
-- Table `events_users_accept`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_users_accept`;

CREATE TABLE IF NOT EXISTS `events_users_accept` (
    `user_id`  INT UNSIGNED NOT NULL,
    `event_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `event_id`),
    INDEX `fk_users_events_accept_events1_idx` (`event_id` ASC),
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`event_id`)
    REFERENCES `events` (`id`)
        ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table `events_users_deny`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_users_deny`;

CREATE TABLE IF NOT EXISTS `events_users_deny` (
    `user_id`  INT UNSIGNED NOT NULL,
    `event_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `event_id`),
    INDEX `fk_users_events_accept_events1_idx` (`event_id` ASC),
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`event_id`)
    REFERENCES `events` (`id`)
        ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Table `images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `images`;

CREATE TABLE IF NOT EXISTS `images` (
    `id`   INT UNSIGNED NOT NULL,
    `url`  VARCHAR(250) NOT NULL,
    `uuid` CHAR(36)     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC),
    UNIQUE INDEX `url_UNIQUE` (`url` ASC),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC)
);

-- -----------------------------------------------------
-- Table `events_images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_images`;

CREATE TABLE IF NOT EXISTS `events_images` (
    `event_id` INT UNSIGNED NOT NULL,
    `image_id` INT UNSIGNED NOT NULL,
    `order`    INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`event_id`, `image_id`),
    INDEX `fk_events_has_images_images1_idx` (`image_id` ASC),
    INDEX `fk_events_has_images_events1_idx` (`event_id` ASC),
    INDEX `image_order` (`order` ASC),
    FOREIGN KEY (`event_id`)
    REFERENCES `events` (`id`),
    FOREIGN KEY (`image_id`)
    REFERENCES `images` (`id`)
);

-- -----------------------------------------------------
-- Table `images_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `images_users`;

CREATE TABLE IF NOT EXISTS `images_users` (
    `user_id`  INT UNSIGNED NOT NULL,
    `image_id` INT UNSIGNED NOT NULL,
    `order`    INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`user_id`, `image_id`),
    INDEX `fk_users_has_images_images1_idx` (`image_id` ASC),
    INDEX `fk_users_has_images_users1_idx` (`user_id` ASC),
    INDEX `image_order` (`order` ASC),
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`),
    FOREIGN KEY (`image_id`)
    REFERENCES `images` (`id`)
);

-- -----------------------------------------------------
-- Table `interests`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `interests`;

CREATE TABLE IF NOT EXISTS `interests` (
    `id`   INT UNSIGNED NOT NULL,
    `name` VARCHAR(50)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC)
);

-- -----------------------------------------------------
-- Table `interests_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `interests_users`;

CREATE TABLE IF NOT EXISTS `interests_users` (
    `user_id`     INT UNSIGNED NOT NULL,
    `interest_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `interest_id`),
    INDEX `fk_users_has_interests_interests1_idx` (`interest_id` ASC),
    INDEX `fk_users_has_interests_users1_idx` (`user_id` ASC),
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`),
    FOREIGN KEY (`interest_id`)
    REFERENCES `interests` (`id`)
);

-- -----------------------------------------------------
-- Table `events_interests`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_interests`;

CREATE TABLE IF NOT EXISTS `events_interests` (
    `event_id`    INT UNSIGNED NOT NULL,
    `interest_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`event_id`, `interest_id`),
    INDEX `fk_events_has_interests_interests1_idx` (`interest_id` ASC),
    INDEX `fk_events_has_interests_events1_idx` (`event_id` ASC),
    FOREIGN KEY (`event_id`)
    REFERENCES `events` (`id`),
    FOREIGN KEY (`interest_id`)
    REFERENCES `interests` (`id`)
);

-- -----------------------------------------------------
-- Table `chats_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chats_users`;

CREATE TABLE IF NOT EXISTS `chats_users` (
    `message_id` INT UNSIGNED NOT NULL,
    `chat_id`    INT UNSIGNED NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `content`    TEXT         NOT NULL,
    `created_at` DATETIME     NOT NULL,
    PRIMARY KEY (`message_id`, `chat_id`, `user_id`),
    INDEX `fk_chat_has_users_users1_idx` (`user_id` ASC),
    INDEX `fk_chat_has_users_chat1_idx` (`chat_id` ASC),
    UNIQUE INDEX `message_id_UNIQUE` (`message_id` ASC),
    INDEX `date_sort` (`chat_id` ASC, `created_at` DESC),
    FOREIGN KEY (`chat_id`)
    REFERENCES `chat` (`id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
);

-- -----------------------------------------------------
-- Table `users_genders_looking_for`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users_genders_looking_for`;

CREATE TABLE IF NOT EXISTS `users_genders_looking_for` (
    `user_id`   INT UNSIGNED NOT NULL,
    `gender_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `gender_id`),
    INDEX `fk_users_has_genders_genders1_idx` (`gender_id` ASC),
    INDEX `fk_users_has_genders_users1_idx` (`user_id` ASC),
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`),
    FOREIGN KEY (`gender_id`)
    REFERENCES `genders` (`id`)
);
