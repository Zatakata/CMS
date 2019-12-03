class EventListener {
    constructor() {
        this.events = {};
    }

    on(type, callback) {
        this.events[type] = this.events[type] || [];

        this.events[type].push(callback);
    }

    emit(type, value) {
        this.events[type].forEach(callback => {
            callback(value);
        })
    }
}

class RuModel {
    constructor() {
        this.state = {
            title: 'Ваш шедевр готов!',
            text: 'Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции требуют определения и уточнения дальнейших направлений развития. Таким образом новая модель организационной деятельности играет важную роль в формировании дальнейших направлений развития.',
            author: 'Рыба',
            comments: []
        }
    }

    getText() {
        return this.state
    }

    addComment(comment) {
        this.state.comments.push(comment)
    }

}

class EnModel {
    constructor() {
        this.state = {
            title: 'Your masterpiece is ready!',
            text: 'The significance of these problems is so obvious that the beginning of daily work on the formation of a position requires the identification and refinement of further areas of development. Thus, the new model of organizational activity plays an important role in shaping further directions of development.',
            author: 'fish',
            img: 'https://cdn.pixabay.com/photo/2016/06/11/20/27/fish-1450768_960_720.png'
        }
    }

    getText() {
        return this.state
    }
}

class RuView extends EventListener {
    constructor() {
        super()
        this.app = document.querySelector('#app')  
    }

    render(data) {
        const {title, text, author, comments} = data
        
        const template = `
            <nav>
                <ul>
                    <li><a href="#ru">ru</a></li>
                    <li><a href="#en">en</a></li>
                </ul>
            </nav>

            <h2>${title}</h2>
            <p>${text}</p>
            <b>${author}</b>
            <h4>Оставить комментарий</h4>
            <div>
                <textarea cols="30" rows="3" id="comment"></textarea>
                <br>
                <button id="sendComment">Отправить</button>
            </div>
            <br>
            <section class="comments">${comments.map(comment => '<li>' + comment + '</li>').join(' ') }</section>
        `

        this.app.innerHTML = template;

        document.querySelector('#sendComment').addEventListener('click', () => {
            this.handlerComment()
        })
    }

    handlerComment() {
        let commentText = document.querySelector('#comment').value
        this.emit('addComment', commentText)
    }
}

class EnView {
    constructor() {
        this.app = document.querySelector('#app')
    }

    render(data) {
        const {title, text, author, img} = data
        
        const template = `
            <nav>
                <ul>
                    <li><a href="#ru">ru</a></li>
                    <li><a href="#en">en</a></li>
                </ul>
            </nav>

            <h2>${title}</h2>
            <p>${text}</p>
            <b>${author}</b>
            <img src="${img}" width="150px">
        `

        this.app.innerHTML = template;
    }
}

class RuController {
    constructor(model, view) {
        this.model = model
        this.view = view

        this.view.on('addComment', this.addComment.bind(this))

        this.showPage()
    }

    showPage() {
        const data = this.model.getText()
        this.view.render(data)
    }

    addComment(commentText) {
        this.model.addComment(commentText)

        const data = this.model.getText()
        this.view.render(data)
    }
}

class EnController {
    constructor(model, view) {
        this.model = model
        this.view = view

        this.showPage()
    }

    showPage() {
        const data = this.model.getText()
        this.view.render(data)
    }
}

class Router {
    constructor(routes = []) {
        this.routes = routes || []

        this.controllers = {
            'ruController': RuController,
            'enController': EnController,
        }

        this.models = {
            'ruModel': RuModel,
            'enModel': EnModel,
        }

        this.view = {
            'ruView': RuView,
            'enView': EnView,
        }

        addEventListener('hashchange', () => {
            if (!this.checkRoute(location.hash.slice(1)))
                document.getElementById('app').innerHTML = '<div class="container">Страница не найдена!</div>'
        })

        this.checkRoute('ru')
    }

    checkRoute(hash) {
        
        let app = document.getElementById('app')
        let res = this.routes.some(route => {
            if (route === hash) {
                app.innerHTML = ''
                const controllerName = hash + 'Controller'
                const modelName = hash + 'Model'
                const viewName = hash + 'View'
                const controller = new this.controllers[controllerName](new this.models[modelName](), new this.view[viewName]())

                return true
            }
        })
        return res
    }
}


const router = new Router(['ru', 'en'])


