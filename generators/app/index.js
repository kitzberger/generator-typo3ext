'use strict'

const BaseGenerator = require('../base.js')
    , yosay = require('yosay')
    , chalk = require('chalk')
    , glob = require('glob')

module.exports = class extends BaseGenerator {

  async prompting() {
    await super.prompting()
  }

  writing() {
    var files = glob.sync('*', {dot: true, nodir: true, cwd: this.templatePath()})

    if (files.length) {
      this.log(chalk.green('Creating ' + files.length + ' files'))

      for (var i=0; i<files.length; i++) {
        var targetName = files[i].replace(/^[_]/, '')
        this.log('Creating ' + targetName)
        this.fs.copyTpl(
          this.templatePath(files[i]),
          this.destinationPath(targetName),
          this.config.getAll()
        )
      }
    } else {
      this.log(chalk.red('No templates files found!'))
    }
  }

  install() {
  }

  end() {
    this.log(chalk.green('Done with everything!'))
  }
}
