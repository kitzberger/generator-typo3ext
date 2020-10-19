# generator-typo3ext

> This kickstarts a simple TYPO3 extension

## Installation

First, install [Yeoman](http://yeoman.io) and generator-typo3ext using [npm](https://www.npmjs.com/) (we assume you have pre-installed [node.js](https://nodejs.org/) 10.x).

```bash
npm install -g yo
```

### Via npm (not possible yet)

```
npm install -g generator-typo3ext
```

### Via git

```
git clone https://github.com/kitzberger/generator-typo3ext.git
cd generator-typo3ext
npm link
```

## Usage

Generate your new extension:

```bash
mkdir typo3conf/ext/new_extension
cd typo3conf/ext/new_extension

yo typo3ext
```

Need some CLI command controller?

```bash
yo typo3ext:cli [--skip-prompting]
```

Need some TCA extending?

```bash
yo typo3ext:tca-extend [--skip-prompting]
```

Need some new slug field?

```bash
yo typo3ext:tca-slug [--skip-prompting]
```

Need some upgrade wizard class?

```bash
yo typo3ext:update [--skip-prompting]
```

## License

MPL-2.0 © [Philipp Kitzberger](https://github.com/kitzberger)
