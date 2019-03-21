'use strict';

const BaseGenerator = require('../base.js');
const yosay = require('yosay');
const chalk = require('chalk');
const ejs = require('ejs');

module.exports = class ModelGenerator extends BaseGenerator {
  constructor(args, opts) {
    super(args, opts);

    this.argument('modelName', {
      desc: 'Should be singular',
      type: String,
      required: false
    });
    this.option('write', { desc: 'Write/update files', default: false });
  }

  async prompting() {
    await super._promptingBasic();
    await this._promptingModel();
  }

  async _promptingModel() {
    if (!this.options['skip-welcome']) {
      this.log(
        yosay(
          "Alright! Let's create some model for our TYPO3 extension together, shall we?"
        )
      );
    }

    let models = this.config.get('models');
    if (models === undefined) {
      models = {};
    } else {
      this.log('These models are already in the config:');
      console.dir(models);
    }

    if (this.options.modelName) {
      if (models[this.options.modelName]) {
        this.log(chalk.red('That model is already definied in .yo-rc.json!'));
        process.exit();
      } else {
        this.log(
          chalk.blue('\nSo let\'s create this new model "' + this.options.modelName + '"')
        );
      }

      this.model = {
        name: this.options.modelName,
        properties: []
      };

      var wantToPromptForProperties = await this.prompt([
        {
          type: 'confirm',
          name: 'continue',
          message: 'Want to define some properties?',
          default: true
        }
      ]);

      let fqnPrefix =
        '\\' +
        this.config.get('VendorName') +
        '\\' +
        this.config.get('ExtKey') +
        '\\Domain\\Model\\';

      if (wantToPromptForProperties.continue === true) {
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
                'string',
                'text',
                'integer',
                'boolean',
                'date',
                'datetime',
                {
                  name: fqnPrefix + '...',
                  value: 'relation-model'
                },
                {
                  name: '\\Vendor\\Extension\\Domain\\Model\\...',
                  value: 'relation-class'
                },
                {
                  name:
                    '\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage<' +
                    fqnPrefix +
                    '...>',
                  value: 'relation-mm-model'
                },
                {
                  name:
                    '\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage<\\Vendor\\Extension\\Domain\\Model\\...>',
                  value: 'relation-mm-class'
                }
              ],
              default: 'string'
            },
            {
              type: 'input',
              name: 'model',
              message:
                'Please name the domain model for this relation (model of this extension):',
              when: function(answers) {
                return answers.type === 'relation-model';
              }
            },
            {
              type: 'input',
              name: 'class',
              message: 'Please name the class for this relation (absolute class name):',
              when: function(answers) {
                return answers.type === 'relation-class';
              }
            },
            {
              type: 'input',
              name: 'model',
              message:
                'Please name the domain model for this mm-relation (model of this extension):',
              default: '',
              when: function(answers) {
                return answers.type === 'relation-mm-model';
              }
            },
            {
              type: 'input',
              name: 'class',
              message:
                'Please name the domain model for this mm-relation (absolute class name):',
              default: '',
              when: function(answers) {
                return answers.type === 'relation-mm-class';
              }
            },
            {
              type: 'confirm',
              name: 'continue',
              message: 'More properties?',
              default: true
            }
          ]);

          if (property.type === 'relation-model') {
            property.relation = fqnPrefix + property.model;
          }
          if (property.type === 'relation-class') {
            property.relation = property.class;
          }

          this.model.properties.push({
            name: property.name,
            type: property.type,
            relation: property.relation
          });
        } while (property.continue);
      }

      console.dir(this.model);

      models[this.model.name] = this.model.properties;
      this.config.set('models', models);
    }
  }

  writing() {
    if (this.options.write === true) {
      var variables = this.config.getAll();

      if (variables.models === undefined) {
        this.log(chalk.red('No models to write!'));
        process.exit();
      }

      for (let [modelName, modelProperties] of Object.entries(variables.models)) {
        variables.modelName = modelName.lcfirst();
        variables.ModelName = modelName.ucfirst();
        variables.table =
          'tx_' + variables.extkey + '_domain_model_' + variables.modelName;
        this._writingModelFiles(modelName, modelProperties, variables);
        this._writingSQL(modelName, modelProperties, variables);
      }
    }
  }

  _writingModelFiles(modelName, modelProperties, variables) {
    this.log(chalk.yellow('Writing model "' + variables.ModelName + '"'));

    // Step 1: create files, if necessary
    var files = [
      [
        'Classes/Domain/Model/ModelTemplate.php',
        'Classes/Domain/Model/' + variables.ModelName + '.php'
      ],
      [
        'Classes/Domain/Repository/ModelRepository.php',
        'Classes/Domain/Repository/' + variables.ModelName + 'Repository.php'
      ],
      [
        'Resources/Private/Language/table.xlf',
        'Resources/Private/Language/' + variables.table + '.xlf'
      ]
    ];

    for (let [source, target] of files) {
      if (this.fs.exists(target) === false) {
        this.fs.copyTpl(
          this.templatePath(source),
          this.destinationPath(target),
          variables
        );
      } else {
        this.log('File already existing: ' + target);
      }

      // Step 2: modifying files
      switch (source) {
        case 'Classes/Domain/Model/ModelTemplate.php':
          for (let property of modelProperties) {
            this._writingModelProperty(property, variables);
          }
          break;
        case 'Resources/Private/Language/table.xlf':
          for (let property of modelProperties) {
            this._writingLanguage(property, variables);
          }
          break;
        default:
      }
    }
  }

  _writingModelProperty(property, variables) {
    variables.propertyName = property.name;
    variables.PropertyName = property.name.ucfirst();
    variables.propertyType = property.type;
    variables.propertyDefault = 'null';

    this.log(
      chalk.yellow(
        'Writing model property "' +
          variables.ModelName +
          '->' +
          variables.propertyName +
          '"'
      )
    );

    var source = this.templatePath('Classes/Domain/Model/ModelProperties.php');
    var target = this.destinationPath(
      'Classes/Domain/Model/' + variables.ModelName + '.php'
    );
    var sourceContent = this.fs.read(source);
    var targetContent = this.fs.read(target);

    var snippet = '';

    var simpleTypes = ['string', 'text', 'integer', 'boolean'];

    if (simpleTypes.indexOf(property.type) >= 0) {
      if (property.type === 'text') {
        property.type = 'string';
      }

      snippet = this._getTemplateSnippet('PROPERTY_DEF', sourceContent);
      snippet = ejs.render(snippet, variables);

      if (snippet.length > 0) {
        if (targetContent.indexOf(snippet) < 0) {
          targetContent = targetContent.replace(
            '// END_PROPERTY_DEF',
            snippet + '\n\n// END_PROPERTY_DEF'
          );
        }
      }

      snippet = this._getTemplateSnippet('PROPERTY_XETTERS', sourceContent);
      snippet = ejs.render(snippet, variables);

      if (snippet.length > 0) {
        if (targetContent.indexOf(snippet) < 0) {
          targetContent = targetContent.replace(
            '// END_PROPERTY_XETTERS',
            snippet + '\n\n// END_PROPERTY_XETTERS'
          );
        }
      }
    }

    this.fs.write(target, targetContent);
  }

  _writingLanguage(property, variables) {
    variables.property = property.name.lcfirst();
    variables.PropertyName = property.name.ucfirst();

    this.log(
      chalk.yellow(
        'Writing language file "' + variables.table + '" (' + variables.property + ')'
      )
    );

    var source = this.templatePath('Resources/Private/Language/property.xlf');
    var target = this.destinationPath(
      'Resources/Private/Language/' + variables.table + '.xlf'
    );
    var sourceContent = this.fs.read(source);
    var targetContent = this.fs.read(target);

    var snippet = '';

    snippet = this._getTemplateSnippet('PROPERTY_LABEL', sourceContent);
    snippet = ejs.render(snippet, variables);

    if (snippet.length > 0) {
      if (targetContent.indexOf(snippet) < 0) {
        // Only replace if _not_ already in target file
        targetContent = targetContent.replace(/(\s*<\/body>)/, '\n' + snippet + '$1');
      }
    }

    this.fs.write(target, targetContent);
  }

  _writingSQL(modelName, modelProperties, variables) {
    this.log(chalk.yellow('Writing SQL file "ext_tables.sql" (' + variables.table + ')'));

    var source = this.templatePath('ext_tables.sql');
    var target = this.destinationPath('ext_tables.sql');

    if (this.fs.exists(target) === false) {
      this.log('Creating ' + target);
      this.fs.write(target, '');
    } else {
      this.log('File already existing: ' + target);
    }

    var sourceContent = this.fs.read(source);
    var targetContent = this.fs.read(target);

    var snippet = '';

    var tableCreateStatement = 'CREATE TABLE ' + variables.table;

    // Create table
    if (targetContent.indexOf(tableCreateStatement) < 0) {
      snippet = this._getTemplateSnippet('TABLE', sourceContent);
      snippet = ejs.render(snippet, variables);
      targetContent = targetContent + '\n' + snippet;
    }

    // Get table statement
    var regex = new RegExp('^' + tableCreateStatement + ' (.*)', 'gms');
    var targetContentTableBefore = regex.exec(targetContent)[0];
    var targetContentTableAfter = targetContentTableBefore;

    // Foreach properties
    for (let property of modelProperties) {
      variables.field = property.name;
      let sqlType;
      switch (property.type) {
        case 'integer':
          sqlType = 'INT';
          break;
        case 'boolean':
          sqlType = 'BOOLEAN';
          break;
        case 'string':
          sqlType = 'VARCHAR';
          break;
        default:
          sqlType = 'TEXT';
      }
      snippet = this._getTemplateSnippet('FIELD_' + sqlType, sourceContent);
      snippet = ejs.render(snippet, variables);

      if (targetContentTableAfter.indexOf(snippet) < 0) {
        targetContentTableAfter = targetContentTableAfter.replace(
          '\n);',
          ',\n' + snippet + '\n);'
        );
      }
    }

    // Set table statement
    targetContent = targetContent.replace(
      targetContentTableBefore,
      targetContentTableAfter
    );

    // Write file
    this.fs.write(target, targetContent);
  }

  _getTemplateSnippet(snippetName, content) {
    var regex = new RegExp(
      '^[/-]{2} BEGIN_' + snippetName + '$([\\s\\S]*)^[/-]{2} END_' + snippetName + '$',
      'gms'
    );
    var snippet = regex.exec(content);
    // This.log(regex);
    // this.log(snippet);
    return snippet[1].replace(/^\n+|\n+$/g, '');
  }

  install() {}

  end() {
    this.log(chalk.green('Done with everything!'));
  }
};
