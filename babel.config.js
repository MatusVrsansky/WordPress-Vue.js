module.exports = {

    "presets": [
        [
            "@babel/preset-env", {
            useBuiltIns: "entry",
            corejs: 3,
            modules: false
        }]
    ],
    "plugins": [
        [
            "component",
            {
                "libraryName": "element-ui",
                "style": false
            }
        ]
    ]
};
