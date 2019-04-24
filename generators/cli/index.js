'use strict';

const BaseGenerator = require('../base.js');
const yosay = require('yosay');
const chalk = require('chalk');
const ejs = require('ejs');

module.exports = class extends BaseGenerator {
  async prompting() {
    await super._promptingBasic();
    await this._promptingCli();
  }

  async _promptingCli() {
    if (!this.options['skip-welcome-message']) {
      this.log(
        yosay(
          "Alright! Let's create some CLI task for our TYPO3 extension together, shall we?"
        )
      );
    }

    var cliPrompts = [
      {
        type: 'list',
        name: 'type',
        message: 'What kind of CLI command?',
        choices: [
          {
            name: 'Symfony (TYPO3 >= 8.0)',
            value: 'symfony'
          },
          {
            name: 'Extbase',
            value: 'extbase'
          }
        ]
      },
      {
        type: 'input',
        name: 'command',
        message: 'Name of Symfony command?',
        when: function(response) {
          return response.type === 'symfony';
        }
      },
      {
        type: 'input',
        name: 'controller',
        message: 'Name of Extbase command controller?',
        when: function(response) {
          return response.type === 'extbase';
        }
      },
      {
        type: 'input',
        name: 'command',
        message: 'Name of Extbase command itself?',
        when: function(response) {
          return response.type === 'extbase';
        }
      }
    ];

    this.cliAnswers = await this.prompt(cliPrompts);
  }

  writing() {
    var variables = this.config.getAll();
    variables.type = this.cliAnswers.type;
    variables.controller = this.cliAnswers.controller;
    variables.command = this.cliAnswers.command.lcfirst();
    variables.Command = this.cliAnswers.command.ucfirst();

    var source;
    var target;

    if (variables.type === 'symfony') {
      // File 1
      source = 'Classes/Command/Command.php';
      target = 'Classes/Command/' + variables.Command + 'Command.php';

      this.log('Creating ' + target);
      this.fs.copyTpl(this.templatePath(source), this.destinationPath(target), variables);

      // File 2
      source = 'Configuration/Commands.php';
      target = 'Configuration/Commands.php';

      this.log('Creating ' + target);
      this.fs.copyTpl(this.templatePath(source), this.destinationPath(target), variables);
    } else {
      // File 1
      source = 'Classes/Command/CommandController.php';
      target = 'Classes/Command/' + variables.controller + 'CommandController.php';

      this.log('Creating ' + target);
      this.fs.copyTpl(this.templatePath(source), this.destinationPath(target), variables);

      // File 2
      source = this.templatePath('ext_localconf.php');
      target = this.destinationPath('ext_localconf.php');

      if (this.fs.exists(target) === false) {
        this.fs.write(target, '<?php\n\n');
      }

      this.log('Modifying ' + target);
      var sourceContent = this.fs.read(source);
      // Var targetContent = this.fs.read(target);

      var regex = /^\/\/ BEGIN$([\s\S]*)^\/\/ END$/gm;
      var snippet = regex.exec(sourceContent);

      var content = ejs.render(snippet[1], variables);

      this.fs.append(target, content);
    }
  }

  install() {}

  end() {
    this.log(chalk.green('Done with everything!'));
  }
};
