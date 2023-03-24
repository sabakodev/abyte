SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `domains` (
  `id` text NOT NULL,
  `name` text NOT NULL,
  `owner` int(11) NOT NULL,
  `point4` text,
  `point6` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `domains` (`id`, `name`, `owner`, `point4`, `point6`, `status`) VALUES
('reserved', 'www', 0, NULL, NULL, 0);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `mail` text NOT NULL,
  `pass` text NOT NULL,
  `type` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `users` (`id`, `name`, `mail`, `pass`, `type`) VALUES
(1, 'WebMaster', 'web@abyte.site', 'x', 0);

ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`(64));

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;