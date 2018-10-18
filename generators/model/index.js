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
          "Alright! Let's create some model for our TYPO3 extension together, shall we?"
        )
      );
    }

    let models = this.config.get('models');
    if (models !== undefined) {
      this.log('These models are already in the config:');
      console.dir(models);
    } else {
      models = {};
    }

    if (this.options.skipPrompting) {
      if (Object.keys(models).length) {
        this.log(chalk.green('Skip prompting, reading .yo-rc.json instead!'));
        return;
      }
      this.log(chalk.yellow('Sorry, cannot skip prompting, no models defined in .yo-rc.json!'));
    }

    this.model = await this.prompt([
      {
        type: 'input',
        name: 'name',
        message: 'Name of model?'
      }
    ]);

    this.model.properties = [];

    do {
      var property = await this.prompt([
        {
          type: 'input',
          name: 'name',
          message: 'Name of the property?'
        },
        {
          type: 'list',
          name: 'type',
          message: 'Type of the property?',
          choices: [
            'string', 'integer', 'boolean', 'date', 'datetime',
            '\\Vendor\\Extension\\Domain\\Model\\...',
            '\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage<\\Vendor\\Extension\\Domain\\Model\\...>'
          ],
          default: 'string'
        },
        {
          type: 'confirm',
          name: 'continue',
          message: 'More properties?',
          default: true
        }
      ]);
      this.model.properties.push({
        name: property.name,
        type: property.type
      });
    } while (property.continue);

    console.dir(this.model);

    models[this.model.name] = this.model.properties;
    this.config.set('models', models);
  }

  writing() {
    var variables = this.config.getAll();
    //variables.command = this.cliAnswers.command.lcfirst();
    //variables.Command = this.cliAnswers.command.ucfirst();

    Object.keys(variables.models).forEach(
      function(modelName, index) {
        //console.dir(index);
        //console.dir(modelName);
        //console.dir(variables.models[modelName]);
        this._writingModel(modelName, variables.models[modelName], variables);
      },
      this
    )
  }

  _writingModel(modelName, modelProperties, variables) {
    var variables = variables;
    variables.modelName = modelName.lcfirst();
    variables.ModelName = modelName.ucfirst();

    // Step 1: create file, if necessary
    var source = 'Classes/Domain/Model/ModelTemplate.php';
    var target = 'Classes/Domain/Model/' + variables.ModelName + '.php';

    if (this.fs.exists(target) === false) {
      this.log('Creating ' + target);
      this.fs.copyTpl(this.templatePath(source), this.destinationPath(target), variables);
    } else {
      this.log('Class already existing: ' + target);
    }

    // Step 2: modify file
    source = this.templatePath('Classes/Domain/Model/ModelProperties.php');
    target = this.destinationPath('Classes/Domain/Model/' + variables.ModelName + '.php');

    this.log('Modifying ' + target);
    var sourceContent = this.fs.read(source);
    var targetContent = this.fs.read(target);

    var snippet = this._getTemplateSnippet('PROPERTY_DEF', sourceContent);

    var content = ejs.render(snippet, variables);

    this.fs.append(targetContent, content);
  }

  _writingProperty(propertyName, propertyType, variables) {

  }

  _getTemplateSnippet(snippetName, content) {
    var regex = new RegExp('^// BEGIN_' + snippetName + '$([\\s\\S]*)^// END_' + snippetName + '$', 'gms');
    var snippet = regex.exec(content);
    //this.log(regex);
    //this.log(snippet);
    return snippet[1];
  }

  install() {}

  end() {
    this.log(chalk.green('Done with everything!'));
  }
};
