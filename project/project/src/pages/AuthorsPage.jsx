import { useState, useEffect } from "react"
import { Link, useLocation } from "react-router-dom";

import axios, {isAxiosError} from "axios";

const AutorsPage = (props) => {

    let [authors, setAuthors] = useState([]);

    let Header = "";
    let Text = "";

    useEffect(() => {
        axios
        .post(`http://fanlib-api.ru/select/authors`,null,{params:{
            'type': props.type,
        }})
        .then((response) => {
            setAuthors(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
        
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