package main

import (
    "os"
    "log"
    "github.com/mikespook/golib/signal"
    "github.com/mikespook/gearman-go/worker"
)

func Reverse(job *worker.Job) ([]byte, error) {
    log.Printf("Reverse: Handle=[%s]; UID=[%s], Data=[%s]\n",
        job.Handle, job.UniqueId, job.Data)
    runes := []rune(string(job.Data))
    for i, j := 0, len(runes)-1; i < j; i, j = i+1, j-1 {
        runes[i], runes[j] = runes[j], runes[i]
    }
    data := []byte(string(runes))
    return data, nil
}

func main() {
    log.Println("Starting ...")
    defer log.Println("Shutdown complete!")
    w := worker.New(worker.Unlimited)
    defer w.Close()
    w.ErrHandler = func(e error) {
        log.Println(e)
        if e == worker.ErrConnection {
            proc, err := os.FindProcess(os.Getpid())
            if err != nil {
                log.Println(err)
            }
            if err := proc.Signal(os.Interrupt); err != nil {
                log.Println(err)
            }
        }
    }
    w.JobHandler = func(job *worker.Job) error {
        log.Printf("H=%s, UID=%s, Data=%s, DataType=%d\n", job.Handle,
            job.UniqueId, job.Data, job.DataType)
        return nil
    }
    w.AddServer("127.0.0.1:4730")
    w.AddFunc("reverse", Reverse, worker.Immediately)
    go w.Work()
    sh := signal.NewHandler()
    sh.Bind(os.Interrupt, func() bool {return true})
    sh.Loop()
}