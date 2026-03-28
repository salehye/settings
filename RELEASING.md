# Releasing Guide

This document describes the release process for the Salehye Settings package.

## Versioning

This package follows [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible changes
- **MINOR** version for backwards-compatible features
- **PATCH** version for backwards-compatible bug fixes

## Release Process

### 1. Update CHANGELOG.md

Add a new section with the version number and date:

```markdown
## [1.0.1] - 2026-03-28

### Fixed
- Bug fix description

### Added
- New feature description
```

### 2. Update Version Number

Update the version in any relevant files (if applicable).

### 3. Commit Changes

```bash
git add .
git commit -m "Release version 1.0.1"
```

### 4. Create Git Tag

```bash
git tag -a 1.0.1 -m "Release version 1.0.1"
git push origin --tags
```

### 5. Publish to Packagist

Packagist will automatically detect the new tag and publish the release.

Or manually update at: https://packagist.org/packages/salehye/settings

### 6. Create GitHub Release

1. Go to https://github.com/salehye/settings/releases
2. Click "Create a new release"
3. Select the tag
4. Add release notes from CHANGELOG.md
5. Publish

## Release Checklist

- [ ] All tests pass
- [ ] Code is formatted
- [ ] CHANGELOG.md is updated
- [ ] README.md is updated (if needed)
- [ ] Documentation is updated (if needed)
- [ ] Git tag is created
- [ ] GitHub release is published
- [ ] Packagist is updated

## Hotfix Releases

For critical bug fixes:

1. Create a hotfix branch from the last release tag
2. Fix the bug
3. Update PATCH version
4. Follow the release process

## Major Releases

For major releases with breaking changes:

1. Create a new branch (e.g., `2.x`)
2. Develop on that branch
3. Update MAJOR version
4. Document all breaking changes
5. Create upgrade guide
6. Follow the release process
