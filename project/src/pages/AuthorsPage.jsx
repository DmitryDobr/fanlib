import { useState, useEffect } from "react"
import { Link, useLocation } from "react-router-dom";

const AutorsPage = (props) => {

    let [authors, setAuthors] = useState([]);

    let Header = "";
    let Text = "";

    useEffect(() => {
        if (props.type === "new")
        {
            fetch('http://fanlib-api.ru/users/new')
            .then(res => res.json())
            .then(data => setAuthors(data))
        }
        if (props.type === "popular")
        {
            setAuthors(null)
        }
        if (props.type === "random")
        {
            setAuthors(null)
        }
    }, [props])


    switch (props.type) {
        case "new":
            Header = "Новые авторы";
            // Text = "Тут будут размещаться новые авторы";
            break;
        
        case "popular":
            Header = "Популярные авторы";
            Text = "Тут будут размещаться популярные авторы";
            break;
        
        case "random":
            Header = "Случайные авторы";
            Text = "Тут будут размещаться случайно выбранные авторы";
            break;
    
        default:
            break;
    }

    if (authors != null)
    {
        return (
            <div className="content">
                <h1>{Header}</h1>
                <p>{Text}</p>
                <div className="AuthorsList">
                    {
                        authors.map(author => (
                            <div className="AuthorsList-element" key={author.user_id}>
                                <Link className="AuthorsList-link" to = {`/author/${author.user_id}`}>
                                    {author.nickname}
                                </Link>
                            </div>
                        ))
                    }
                </div>
            </div>
        )
    }
    else
    {
        return (
            <div className="content">
                <h1>{Header}</h1>
                <p>{Text}</p>
            </div>
        )
    }
}

export {AutorsPage}