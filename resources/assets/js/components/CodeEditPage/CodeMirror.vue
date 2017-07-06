<template>
    <div>
        <textarea :id="uniqname" name="code" class="col-md-12"></textarea>
    </div>
</template>

<script>

    export default{

        props: ['textForEdit', 'autocompleteWords', 'markRows', 'uniqname', 'index', 'watchText'],
        data: function () {

            let interactiveTextArea = {};
            let editContentTextTemp = '';
            let markedText = [];
            let currentLineText = '';
            let textForEdit = '';
            return {interactiveTextArea, editContentTextTemp, markedText, currentLineText}
        },
        watch: {

            textForEdit: {

                handler: function (val) {

                    if(this.watchText === "true"){

                        this.interactiveTextArea.setValue(val);
                    }


                },
                deep: true
            },

            markRows: function (val) {

                for (let key in  this.markedText) {

                    this.markedText[key].clear();
                }


                for (let row of val) {

                    this.markedText.push(this.interactiveTextArea.markText(
                        {line: row, ch: 0}, {
                            line: row,
                            ch: 100
                        }, {className: "styled-background"}));
                }


            }


        },

        methods: {


            reverseText: function (text) {

                return text.split("").reverse().join("").trim();

            },

            getLineText: function (text, lineNumber) {

                let splitText = _.split(text, '\n');

                if (splitText.length < lineNumber) {

                    return '';
                }

                return splitText[lineNumber];
            },

            getCurrentWord: function (text, line) {


                let lineText = this.getLineText(text, line);

                if (lineText.length > 2) {

                    const regex = /^\w{3,}/g;
                    let regResult = regex.exec(this.reverseText(lineText));

                    if (regResult !== null) {

                        return this.reverseText(regResult[0]);
                    }

                }


                return null;

            },

            findAutocompleteWords: function (word) {

                return _.filter(this.autocompleteWords, o => {
                    console.log(o,typeof(o));
                  return  ~o.indexOf(word) && o !== word;
                });
            },

            showAutocompleteItems(words, currentWordLength){


                let objectCursor = this.interactiveTextArea.getDoc().getCursor();
                let fromObjectCursor = {};
                fromObjectCursor.line = objectCursor.line;
                fromObjectCursor.ch = objectCursor.ch - currentWordLength;
                var options = {
                    hint: function () {
                        return {
                            from: fromObjectCursor,
                            to: objectCursor,
                            list: words,

                        }
                    }.bind(this),
                    completeSingle: false
                };
                this.interactiveTextArea.showHint(options);
            },

            changeText: function (text, line) {

                this.editContentTextTemp = text;
                this.$emit('changeTextEdit', text, this.index, this.uniqname);
                this.clearMark();
                let word = this.getCurrentWord(text, line);

                if (word !== null) {

                    let autocompleteWords = this.findAutocompleteWords(word, word.length);

                    if (autocompleteWords.length > 0) {

                        this.showAutocompleteItems(autocompleteWords, word.length);

                    }

                }


            },

            clearMark: function () {

                for (let key in  this.markedText) {

                    this.markedText[key].clear();
                }

            },

            addMarkText: function (errors) {

                for (let errorItem of errors) {

                    let rowErrorForMark = parseInt(errorItem.rowError) - 1;

                    this.markedText.push(this.interactiveTextArea.markText(
                        {line: rowErrorForMark, ch: 0}, {
                            line: rowErrorForMark,
                            ch: 100
                        }, {className: "styled-background"}));
                }

            },
        },

        mounted: function () {

            this.interactiveTextArea = new CodeMirror.fromTextArea(document.getElementById(this.uniqname), {
                matchBrackets: true,
                theme: 'monokai',
                mode: "nginx",
                lineNumbers: true,
                styleSelectedText: true


            });


            this.interactiveTextArea.on("change", function (cm, change) {


                let changedText = cm.getValue();
                let changeLine = change.to.line;
                this.changeText(changedText, changeLine);

                return;


            }.bind(this));

            this.interactiveTextArea.on('cursorActivity', function () {

                let currentCursor = this.interactiveTextArea.getDoc().getCursor();

                this.$emit('changeCursor', currentCursor.line, currentCursor.ch);

            }.bind(this));

            if(this.watchText === "false"){

                this.interactiveTextArea.setValue(this.textForEdit);

            }


        }

    }
</script>
<style>
    .CodeMirror {
        border: 1px solid #eee;
        height: auto;
        font-size: 70%
    }
</style>