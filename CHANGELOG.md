# Changelog

All notable changes to `laravel-notify-matrix` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `HasNotificationPreferences` trait exposing `wants`, `setPreference`, `enable`, `disable`, `getPreferences`, `getPreferencesForGroup`, `clearPreferences`, and a `preferences` morph relation.
- `#[NotificationGroup]` attribute for declarative group assignment on notification classes.
- `class_map` config entry for mapping notifications that cannot be annotated.
- Per-group `default_policy` (`opt_in` / `opt_out`) with global fallback.
- `forced` channels per group that bypass stored preferences.
- `EnforcePreferences` listener that filters channels at dispatch time via the `NotificationSending` event.
- `PreferenceRepository` and `GroupResolver` contracts with default Eloquent and attribute-based implementations.
- Publishable config and migration.
