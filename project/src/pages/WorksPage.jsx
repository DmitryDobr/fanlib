import axios, {isAxiosError} from "axios"
import { useEffect, useState } from "react"

import { WorkOverview } from "../components/WorkOverview";

function WorksPage (props) {

    let [works, setWorks] = useState([]);
    let [maxPage, setMPage] = useState(0);
    let [current_page, setCPage] = useState(0);

    useEffect(() => {loadWorks()},[current_page, props]);

    async function loadWorks() {
        axios
        .get(`http://fanlib-api.ru/select/works?type=${props.type}&page=${current_page}`,null,{params:{}})
        .then((response) => {
            setWorks(response.data.works);
            setMPage(response.data.pagecount);
            // console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    function ChangePage(UpDown) {
        if (UpDown === true) // вверх 
        {
            if (current_page+2 <= maxPage)
                setCPage(current_page+1);
        }
        else // вниз
        {
            if (current_page >= 1)
                setCPage(current_page-1);
        }
        console.log(current_page)
    }

    return (
        <div className="content">
            <div className="WorksList">
                <h1>
                    {
                        props.type === 'new' ? <>Новые работы</> : <>Завершенные работы</>
                    }
                </h1>
                
                <>
                    {
                        works !== undefined &&
                        works.map(el => (
                            <WorkOverview WorkId={el.work_id}/>
                        ))
                    }
                </>

                <div className="PageNavContainer">
                    <button className="Input-button" onClick={() => {ChangePage(false)}}>Назад</button>
                    <span>{current_page+1}/{maxPage}</span>
                    <button className="Input-button" onClick={() => {ChangePage(true)}}>Вперед</button>
                </div>

            </div>
        </div>
    )
    

    
}

export {WorksPage}